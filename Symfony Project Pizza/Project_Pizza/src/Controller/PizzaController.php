<?php

namespace App\Controller;

use App\Entity\Bestelling;
use App\Entity\Category;
use App\Entity\Pizzas;
use App\Repository\BestellingRepository;
use App\Repository\CategoryRepository;
use App\Repository\PizzasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\u;

class PizzaController extends AbstractController
{
   /**
    * @Route("/")
    */
    public function show(CategoryRepository $CategoryRepository)
    {
        $pizzaCategories = $CategoryRepository;

        $categories = $pizzaCategories->findAll();

        return $this->render('Pizza/home.html.twig', [
            "pizzaCategories" => $categories
        ]);

    }

    /**
     * @Route("/categories/{id}", name="app_menu")
     */
    public function menu(Category $category)
    {
        return $this->render('Pizza/menu.html.twig', [
            'pizzas' => $category->getPizzas()
        ]);
    }

    /**
     * @Route("/menu")
     */
    public function allPizzas(PizzasRepository $pizzasRepository)
    {
        return $this->render('Pizza/menu.html.twig', [
            'pizzas' => $pizzasRepository->findAll()
        ]);
    }

    /**
     * @Route("/orderpizza/{id}",name="app_order")
     */
    public function new(Pizzas $pizzas, Request $request, BestellingRepository $bestellingRepo): Response
    {

        // creates a task object and initializes some data for this example
        $bestelling = new Bestelling();
        $bestelling->setPizza($pizzas);
        $bestelling->setStatus("ordered");

        $form = $this->createFormBuilder($bestelling)
            ->add('firstname')
            ->add('lastname')
            ->add('city')
            ->add('address')
            ->add('zipcode')
            ->add('size')
            ->add('submit', SubmitType::class, ['label' => 'Order Pizza'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $order = $form->getData();

            // ... perform some action, such as saving the order to the database
            $bestellingRepo->add($bestelling);
            return $this->redirectToRoute('task_succes');
        }

        return $this->renderForm('Pizza/order.html.twig', [
            'form' => $form,
        ]);

    }

    /**
     * @Route("/order/succes",name="task_succes")
     */
    public function succes():Response{
        return $this->render('Pizza/task_succes.html.twig');
    }

    /**
     * @Route("/contact")
     */
    public function contact(): Response
    {
        $contactgegevens = [
            '070 38446647',
            'sopranos@email.com',
        ];

        return $this->render('Pizza/contact.html.twig', [
            'title' => "Dit zijn onze contact gegevens!",
            'contactgegevens' => $contactgegevens,
            'name' => "Berke Kaan Ozturk",
        ]);
    }
}