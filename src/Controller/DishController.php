<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Form\DishType;
use App\Repository\DishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dish')]
final class DishController extends AbstractController
{
    #[Route('', name: 'app_dish_index', methods: ['GET'])]
    public function index(DishRepository $repo): Response
    {
        return $this->render('dish/index.html.twig', [
            'dishes' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_dish_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $dish = new Dish();
        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($dish);
            $em->flush();
            return $this->redirectToRoute('app_dish_index');
        }

        return $this->render('dish/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dish_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Dish $dish, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_dish_index');
        }

        return $this->render('dish/edit.html.twig', [
            'form' => $form,
            'dish' => $dish,
        ]);
    }

    #[Route('/{id}/delete-image', name: 'app_dish_delete_image', methods: ['POST'])]
    public function deleteImage(Request $request, Dish $dish, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete-image' . $dish->getId(), $request->request->get('_token'))) {
            $dish->setImageName(null);
            $em->flush();
            $this->addFlash('success', 'Фото удалено');
        }
        return $this->redirectToRoute('app_dish_edit', ['id' => $dish->getId()]);
    }

    #[Route('/{id}', name: 'app_dish_delete', methods: ['POST'])]
    public function delete(Request $request, Dish $dish, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $dish->getId(), $request->request->get('_token'))) {
            $em->remove($dish);
            $em->flush();
        }
        return $this->redirectToRoute('app_dish_index');
    }
}
