<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\AuthorForm;
use App\Form\BookType;
use App\Service\AuthorMailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/addBook', name: 'addBook')]
    public function addBook(Request $request, EntityManagerInterface $em,AuthorMailerService $mailer)
    {
        $book = new Book(); //instance de l'auteur
        $form = $this->createForm(BookType::class,$book); //lié le formulaire avec l'instance
        $form->handleRequest($request); //recupération http
        if($form->isSubmitted()) //si le user a cliqué sur le bouton submit
        {
            $em->persist($book); //alloué la ligne dans la table
            $em->flush(); //mise a jour
            $mailer->notifyAuthor($book);
            //dump('Mail envoyé à : ' . $book->getAuthor()->getEmail()).die();
            return $this->redirectToRoute('addBook');
        }
        return $this->render('book/add.html.twig',['form'=> $form->createView()]);

    }
}