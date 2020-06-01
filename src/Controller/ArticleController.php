<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article")
     */
    public function index(ArticleRepository $repository)
    {
        $articles = $repository->findAll();
        return $this->render('article/list.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/article/new", name="new_article")
     */
    public function create(Request $request, EntityManagerInterface $manager){
        $article = new Article();
        $form = $this->createForm(ArticleType::class,$article);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('article');
        }
        return $this->render('article/create.html.twig',[
            'formArticle' => $form->createView()
        ]);

    }

    /**
     * @Route("/article/{id}/edit", name="edit_article")
     */
    public function edit(Request $request,Article $article, EntityManagerInterface $manager){
        $form = $this->createForm(ArticleType::class,$article);
        $form->handlerequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('article');
        }
        return $this->render('article/create.html.twig',['formArticle' => $form->createView()]);
    }


     /**
     * @Route("/article/{id}/delete", name="delete_article", methods="DELETE")
     */
    public function delete(Article $article, Request $request,EntityManagerInterface $manager) {
            $manager->remove($article);
            $manager->flush();
        return $this->redirectToRoute('article');
    }

}
