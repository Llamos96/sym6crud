<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ArticleController extends AbstractController
{
  #[Route('/articles', name: 'articles_list')]
  public function index(EntityManagerInterface $entityManager): Response
  {
    $articles = $entityManager->getRepository(Article::class)->findAll();

    return $this->render('articles/index.html.twig', [
        'articles' => $articles
        ]
    );
  }

  #[Route('/article/new', name: 'new_article')]
  public function new(EntityManagerInterface $entityManager, Request $request)
  {
    $article = new Article();

    $form = $this->createFormBuilder($article)
      ->add('title', TextType::class, [
        'attr' => ['class' => 'form-control']])
      ->add('body', TextareaType::class, [
        'required' => false,
        'attr' => ['class' => 'form-control']])
      ->add('save', SubmitType::class, [
        'label' => 'Create',
        'attr' => ['class' => 'btn btn-primary mt-3']])
      ->getForm();  

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()) {
      $article = $form->getData();

      $entityManager->persist($article);
      $entityManager->flush();

      return $this->redirectToRoute('articles_list');
    }
      
    return $this->render('articles/new.html.twig', [
        'form' => $form->createView()
    ]);
  }

  #[Route('/article/edit/{id}', name: 'edit')]
  public function edit(EntityManagerInterface $entityManager, Request $request, int $id)
  {
    $article = $entityManager->getRepository(Article::class)->find($id);

    $form = $this->createFormBuilder($article)
      ->add('title', TextType::class, [
        'attr' => ['class' => 'form-control']])
      ->add('body', TextareaType::class, [
        'required' => false,
        'attr' => ['class' => 'form-control']])
      ->add('save', SubmitType::class, [
        'label' => 'Update',
        'attr' => ['class' => 'btn btn-primary mt-3']])
      ->getForm();  

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()) {

      $entityManager->flush();

      return $this->redirectToRoute('articles_list');
    }
      
    return $this->render('articles/edit.html.twig', [
        'form' => $form->createView()
    ]);
  }

  #[Route('/article/delete/{id}', name: 'delete_article')]
  public function delete(EntityManagerInterface $entityManager, Request $request, int $id)
  {
    $article = $entityManager->getRepository(Article::class)->find($id);

    $entityManager->remove($article);
    $entityManager->flush();

    $response = new Response();
    $response->send();

  }

  #[Route('/article/{id}', name: 'show_article')]
  public function show(EntityManagerInterface $entityManager, int $id): Response 
  {
    $article = $entityManager->getRepository(Article::class)->find($id);

    return $this->render('articles/show.html.twig', [
        'article' => $article
    ]);
  }

  #[Route('/article/create', name: 'create_article')]
  public function createArticle(EntityManagerInterface $entityManager): Response
  {
    $article = new Article;
    $article->setTitle('Article One');
    $article->setBody('This is the content of article 1');

    $entityManager->persist($article);

    $entityManager->flush();

    return new Response('Saved new article with id '.$article->getId());
  }

  // #[Route('/luckynumber/{max}', name: 'app_lucky_number')]
  /**
   * @Route("/number/{max}")
   * @Method({"GET"})
   */
  public function number(int $max): Response
  {
    $number = random_int(0, $max);

    return new Response(
        '<html><body> Lucky Number: '.$number.' </body></htmml>'
    );
  }
}