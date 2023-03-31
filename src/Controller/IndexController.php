<?php
namespace App\Controller;

use App\Form\ArticleType;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;

class IndexController extends AbstractController
{
  private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
   {
      $this->entityManager = $entityManager;
   }
   public function home()
 {
 //récupérer tous les articles de la table article de la BD
 // et les mettre dans le tableau $articles
$articles= $this->entityManager->getRepository(Article::class)->findAll();
return $this->render('articles/index.html.twig',['articles'=> $articles]);
 }

 public function save() {

    $article = new Article();
  $article->setNom('Article 3');
  $article->setPrix(300);

  $this->entityManager->persist($article);
  $this->entityManager->flush();
  return new Response('Article enregisté avec id '.$article->getId());
  }

  public function new(Request $request) {
    $article = new Article(); 
    $form = $this->createForm(ArticleType::class,$article); 
    $form->handleRequest($request); if($form->isSubmitted() && $form->isValid()) {
        $article = $form->getData();
        $this->entityManager->persist($article);
        $this->entityManager->flush();
          return $this->redirectToRoute('article_list'); } 
   return $this->render('articles/new.html.twig',['form' => $form->createView()]); }
 
   
   
  public function show($id) {
    $article = $this->entityManager->getRepository(Article::class)
    ->find($id);
    return $this->render('articles/show.html.twig',
    array('article' => $article));
   }

   public function edit(Request $request, $id) {
    $article = new Article();
   $article = $this->entityManaager->getRepository(Article::class)->find($id);
   
    $form = $this->createForm(ArticleType::class,$article);
   
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
   
    //$entityManager = $this->entityManager->getManager();
    $entityManager->flush();
   
    return $this->redirectToRoute('article_list');
    }
   
    return $this->render('articles/edit.html.twig', ['form' =>
   $form->createView()]);
    }
   

     public function delete(Request $request, $id) {
      $article = $this->entityManager->getRepository(Article::class)->find($id);
     
      $entityManager = $this->entityManager;
      $entityManager->remove($article);
      $entityManager->flush();
     
      $response = new Response();
      $response->send();
      return $this->redirectToRoute('article_list');
      }
}
?>