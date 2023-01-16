<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;


/**
 * @Route("/api", name="api_")
 */
class ArticleController extends AbstractController
{

    public function __construct(private ManagerRegistry $doctrine)
    {}

    /**
     * @Rest\Get("/articles", name="app_articles_list")
     * @Rest\QueryParam(name="order")
     */
    public function index(): Response
    {
        $articles = $this->doctrine
        ->getRepository(Article::class)
            ->findAll();
  
        $data = [];
  
        foreach ($articles as $article) {
           $data[] = [
            'numarticle' => $article->getNumarticle(),
            'libelle' => $article->getLibelle(),
            'prixunitaire' => $article->getPrixUnitaire(),
            'qtestock' => $article->getQteStock(),
           ];
        }
        return $this->json($data);
    }
    
    /**
     * @Rest\Post(
     *    path = "/article",
     *    name = "app_article_create"
     * )
     * @QueryParam(name="numarticle" , description="Numero de l'article", nullable=false, allowBlank=false)
     * @QueryParam(name="libelle" , description="libelle", nullable=false, allowBlank=false)
     * @QueryParam(name="prixunitaire" , description="Prix Unitaire", nullable=false, allowBlank=false)
     * @QueryParam(name="qtestock" , description="Quantité en stock", nullable=false, allowBlank=false)
     */
    public function new(ArticleRepository $articleRepository, Request $request): Response
    {
        $entityManager = $this->doctrine->getManager();

        $article = new Article();
        $article->setNumArticle($request->get('numarticle'));
        $article->setLibelle($request->get('libelle'));
        $article->setPrixUnitaire($request->get('prixunitaire'));
        $article->setQteStock($request->get('qtestock'));
        $existe = $articleRepository->findOneByNumarticle($article->getNumArticle());
        if ($existe) {
            return $this->json('Article existe deja');
        }
        $entityManager->persist($article);
        $entityManager->flush();
        return $this->json('Created new article successfully with id ' . $article->getNumArticle());
    }
  
    /**
     * @Rest\Get("/article/{id}", name="app_article_list")
     */
    public function show(ArticleRepository $articleRepository, int $id): Response
    {
        $article = $articleRepository->findOneByNumarticle($id);
        if (!$article) {
  
            return $this->json('No article found for id ' . $id, 404);
        }
  
        $data =  [
            'numarticle' => $article->getNumArticle(),
            'libelle' => $article->getLibelle(),
            'prixunitaire' => $article->getPrixUnitaire(),
            'qtestock' => $article->getQteStock(),
        ];
          
        return $this->json($data);
    }
  
    /**
     * @Rest\Put(
     *    path = "/article/{id}",
     *    name = "article_edit"
     * )
     * @QueryParam(name="libelle" , description="libelle", nullable=false, allowBlank=false)
     * @QueryParam(name="prixunitaire" , description="Prix Unitaire", nullable=false, allowBlank=false)
     * @QueryParam(name="qtestock" , description="Quantité en stock", nullable=false, allowBlank=false)
     */
    public function edit(ArticleRepository $articleRepository,Request $req,int $id): Response
    {
        $entityManager = $this->doctrine->getManager();
        $article = $articleRepository->findOneByNumarticle($id);

        if (!$article) {
            return $this->json('No article found for id ' . $id, 404);
        }

        $article->setLibelle($req->get('libelle'));
        $article->setPrixUnitaire($req->get('prixunitaire'));
        $article->setQteStock($req->get('qtestock'));
        $entityManager->flush();
  
        $data =  [
            'numarticle' => $article->getNumArticle(),
            'libelle' => $article->getLibelle(),
            'prixunitaire' => $article->getPrixUnitaire(),
            'qtestock' => $article->getQteStock(),
        ];
          
        return $this->json($data);
    }
  
    /**
     * @Route("/article/{id}", name="article_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $entityManager=$this->doctrine->getManager();
        $article = $entityManager->getRepository(article::class)->find($id);
  
        if (!$article) {
            return $this->json('No article found for id' . $id, 404);
        }
  
        $entityManager->remove($article);
        $entityManager->flush();
  
        return $this->json('Deleted a article successfully with id ' . $id);
    }
}
