<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Repository\CustomerRepository;
use App\Repository\DishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        OrderRepository $orderRepo,
        CustomerRepository $customerRepo,
        DishRepository $dishRepo
    ): Response {
        return $this->render('home/index.html.twig', [
            'total_orders' => $orderRepo->count([]),
            'total_customers' => $customerRepo->count([]),
            'total_dishes' => $dishRepo->count([]),
            'recent_orders' => $orderRepo->findBy([], ['id' => 'DESC'], 5),
        ]);
    }
}
