<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\LigneDevis;
use App\Repository\ArticleRepository;
use App\Repository\ClientRepository;
use App\Repository\DevisRepository;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Decoder\JsonDecoder;
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
class DevisController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine)
    {}

    /**
     * @Route("/devis", name="devis_index", methods={"GET"})
     */
    public function index(): Response
    {
        $devis = $this->doctrine
            ->getRepository(Devis::class)
            ->findAll();
        $data = [];
        $articles = [];
        foreach ($devis as $d) {
            $obj = [
                'numdevis' => $d->getNumDevis(),
                'datedevis' => $d->getDateDevis(),
                'CIN_Client'=>$d->getClient()->getCin()
            ];
            foreach ($d->getLigneDevis() as $value) {
                $obj2 = [
                        'numarticle ' => $value->getArticle()->getNumArticle(),
                        'quantite ' => $value->getQte()
                ];
                array_push($articles, $obj2);
            }
            array_push($obj, array('articles' => $articles));
            array_push($data, $obj);
        }
        return $this->json($data);
    }


    /**
     * @Rest\Post(
     *    path = "/devis",
     *    name = "devis_new"
     * )
     * @QueryParam(name="libelle" , description="libelle", nullable=false, allowBlank=false)
     * @QueryParam(name="prixunitaire" , description="Prix Unitaire", nullable=false, allowBlank=false)
     * @QueryParam(name="qtestock" , description="Quantité en stock", nullable=false, allowBlank=false)
     * @QueryParam(name="client" , description="Quantité en stock", nullable=false, allowBlank=false)
     */
    public function new(ArticleRepository $articleRepo, ClientRepository $clientRepo, Request $request): Response
    {
        $header = $request->headers->get('client_cin');
        $entityManager = $this->doctrine->getManager();
        $allDevis = $this->doctrine->getRepository(Devis::class)->findAll();
        $devis = new Devis();
        if ($allDevis) {$devis->setNumDevis(end($allDevis)->getNumDevis() + 1);}else {$devis->setNumDevis(1);}
        $devis->setDateDevis(new \DateTime());
        $devis->setClient($clientRepo->findOneByCin($header));
        foreach (json_decode($request->getContent()) as $key => $value) {
            $articles = $articleRepo->findOneByNumarticle($value->numarticle);
            $ligneDevis = new LigneDevis();
            $ligneDevis->setDevis($devis);
            $ligneDevis->setArticle($articles);
            $ligneDevis->setQte($value->qte);
            $devis->addLigneDevi($ligneDevis);
            $entityManager->persist($ligneDevis);
        }
        $entityManager->persist($devis);
        $entityManager->flush();

        return $this->json('Created new devis successfully with id ' . $devis->getNumDevis());
    }

    /**
     * @Route("/devis/{id}", name="devis_show", methods={"GET"})
     */
    public function show(DevisRepository $devisRepository, int $id): Response
    {
        $devis = $devisRepository->findOneByNumDevis($id);

        if (!$devis) {

            return $this->json('No devis found for id' . $id, 404);
        }

        $data = [
            'numdevis' => $devis->getNumDevis(),
            'datedevis' => $devis->getDateDevis(),
        ];

        return $this->json($data);
    }

    /**
     * @Route("/devis/{id}", name="devis_edit", methods={"PUT"})
     */
    public function edit(Request $request, int $id): Response
    {
        $entityManager = $this->doctrine->getManager();
        $devis = $entityManager->getRepository(devis::class)->find($id);

        if (!$devis) {
            return $this->json('No devis found for id' . $id, 404);
        }

        $devis->setName($request->request->get('name'));
        $devis->setDescription($request->request->get('description'));
        $entityManager->flush();

        $data = [
            'id' => $devis->getId(),
            'name' => $devis->getName(),
            'description' => $devis->getDescription(),
        ];

        return $this->json($data);
    }

    /**
     * @Route("/devis/{id}", name="devis_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->doctrine->getManager();
        $devis = $entityManager->getRepository(devis::class)->find($id);

        if (!$devis) {
            return $this->json('No devis found for id' . $id, 404);
        }

        $entityManager->remove($devis);
        $entityManager->flush();

        return $this->json('Deleted a devis successfully with id ' . $id);
    }
}