<?php

namespace App\Controller;

use App\Entity\OrderEntity;
use App\Entity\OrderDocument;
use App\Entity\Customer; // Добавь
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Row;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/order')]
final class OrderController extends AbstractController
{
    #[Route('', name: 'app_order_index', methods: ['GET'])]
    public function index(OrderRepository $repo): Response
    {
        return $this->render('order/index.html.twig', [
            'orders' => $repo->findAll(),
        ]);
    }

    #[Route('/my', name: 'app_order_my', methods: ['GET'])]
    public function myOrders(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        /** @var Customer $user */
        $user = $this->getUser();

        return $this->render('order/my_orders.html.twig', [
            'orders' => $user->getOrders(),
        ]);
    }

    #[Route('/new', name: 'app_order_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $order = new OrderEntity();

        if ($this->isGranted('ROLE_USER')) {
            $order->setCustomer($this->getUser());
        }

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($order);
            $em->flush();
            return $this->redirectToRoute('app_order_my');
        }

        return $this->render('order/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_order_show', methods: ['GET'])]
    public function show(OrderEntity $order): Response
    {
        return $this->render('order/show.html.twig', ['order' => $order]);
    }

    #[Route('/{id<\d+>}/edit', name: 'app_order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OrderEntity $order, EntityManagerInterface $em): Response
    {
        if ($this->isGranted('ROLE_USER') && !$order->getCustomer()) {
            $order->setCustomer($this->getUser());
        }

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_order_show', ['id' => $order->getId()]);
        }

        return $this->render('order/edit.html.twig', [
            'form' => $form->createView(),
            'order' => $order,
        ]);
    }

    #[Route('/document/{id}', name: 'app_order_delete_doc', methods: ['POST'])]
    public function deleteDoc(OrderDocument $doc, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $doc->getId(), $request->request->get('_token'))) {
            $orderId = $doc->getOrder()->getId();
            $em->remove($doc);
            $em->flush();
            return $this->redirectToRoute('app_order_show', ['id' => $orderId]);
        }
        return $this->redirectToRoute('app_order_index');
    }

    #[Route('/export', name: 'app_export_orders', methods: ['GET'])]
    public function exportOrders(Connection $connection): Response
    {
        $sql = "
            SELECT 
                o.id,
                COALESCE(c.name, 'Аноним') as customer_name,
                GROUP_CONCAT(d.name, ', ') as dishes
            FROM orders o
            LEFT JOIN customer c ON o.customer_id = c.id
            LEFT JOIN order_entity_dish od ON o.id = od.order_entity_id
            LEFT JOIN dish d ON od.dish_id = d.id
            GROUP BY o.id
            ORDER BY o.id DESC
        ";

        $orders = $connection->executeQuery($sql)->fetchAllAssociative();

        $writer = new Writer();
        $tempFile = tempnam(sys_get_temp_dir(), 'orders_') . '.xlsx';
        $writer->openToFile($tempFile);

        $headerStyle = (new Style())
            ->setFontBold()
            ->setFontSize(14)
            ->setFontColor(Color::WHITE)
            ->setBackgroundColor(Color::rgb(17, 204, 0));

        $writer->addRow(Row::fromValues([
            'ID заказа',
            'Клиент',
            'Блюда'
        ], $headerStyle));

        foreach ($orders as $order) {
            $writer->addRow(Row::fromValues([
                $order['id'],
                $order['customer_name'],
                $order['dishes'] ?? '—'
            ]));
        }

        $writer->close();

        $response = new Response(file_get_contents($tempFile));
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'orders_' . date('Y-m-d_H-i') . '.xlsx'
        );

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', $disposition);
        @unlink($tempFile);

        return $response;
    }
}
