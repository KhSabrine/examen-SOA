<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
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
class ClientController extends AbstractController
{
    /**
     * @Rest\Get("/clients", name="app_clients_list")
     * @Rest\QueryParam(name="order")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $clients = $doctrine
        ->getRepository(Client::class)
            ->findAll();
  
        $data = [];
  
        foreach ($clients as $client) {
           $data[] = [
            'cin' => $client->getCin(),
            'nom' => $client->getNom(),
            'prenom' => $client->getPrenom(),
            'adresse' => $client->getAdresse(),
            'tel' => $client->getTel(),
           ];
        }
        return $this->json($data);
    }
    
    /**
     * @Rest\Post(
     *    path = "/client",
     *    name = "app_client_create"
     * )
     * @QueryParam(name="cin" , description="Numero de l'client", nullable=false, allowBlank=false)
     * @QueryParam(name="nom" , description="nom", nullable=false, allowBlank=false)
     * @QueryParam(name="prenom" , description="Prix Unitaire", nullable=false, allowBlank=false)
     * @QueryParam(name="adresse" , description="Quantité en stock", nullable=false, allowBlank=false)
     * @QueryParam(name="tel" , description="Quantité en stock", nullable=false, allowBlank=false)
     */
    public function new(ManagerRegistry $doctrine,ClientRepository $clientRepository, Request $request): Response
    {
        $entityManager = $doctrine->getManager();

        $client = new Client();
        $client->setCin($request->get('cin'));
        $client->setNom($request->get('nom'));
        $client->setPrenom($request->get('prenom'));
        $client->setAdresse($request->get('adresse'));
        $client->setTel($request->get('tel'));
        $existe = $clientRepository->findOneByCin($client->getCin());
        if ($existe) {
            return $this->json('client existe deja');
        }
        $entityManager->persist($client);
        $entityManager->flush();
        return $this->json('Created new client successfully with id ' . $client->getCin());
    }
  
    /**
     * @Rest\Get("/client/{id}", name="app_client_list")
     */
    public function show(ManagerRegistry $doctrine,clientRepository $clientRepository, int $id): Response
    {
        $client = $clientRepository->findOneByNumclient($id);
        if (!$client) {
  
            return $this->json('No client found for id ' . $id, 404);
        }
  
        $data =  [
            'numclient' => $client->getNumclient(),
            'nom' => $client->getnom(),
            'prenom' => $client->getprenom(),
            'adresse' => $client->getadresse(),
        ];
          
        return $this->json($data);
    }
  
    /**
     * @Rest\Put(
     *    path = "/client/{id}",
     *    name = "client_edit"
     * )
     * @QueryParam(name="nom" , description="nom", nullable=false, allowBlank=false)
     * @QueryParam(name="prenom" , description="Prix Unitaire", nullable=false, allowBlank=false)
     * @QueryParam(name="adresse" , description="Quantité en stock", nullable=false, allowBlank=false)
     */
    public function edit(ManagerRegistry $doctrine,clientRepository $clientRepository,Request $req,int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $client = $clientRepository->findOneByNumclient($id);

        if (!$client) {
            return $this->json('No client found for id ' . $id, 404);
        }

        $client->setnom($req->get('nom'));
        $client->setprenom($req->get('prenom'));
        $client->setadresse($req->get('adresse'));
        $entityManager->flush();
  
        $data =  [
            'numclient' => $client->getNumclient(),
            'nom' => $client->getnom(),
            'prenom' => $client->getprenom(),
            'adresse' => $client->getadresse(),
        ];
          
        return $this->json($data);
    }
  
    /**
     * @Route("/client/{id}", name="client_delete", methods={"DELETE"})
     */
    public function delete(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $client = $entityManager->getRepository(client::class)->find($id);
  
        if (!$client) {
            return $this->json('No client found for id' . $id, 404);
        }
  
        $entityManager->remove($client);
        $entityManager->flush();
  
        return $this->json('Deleted a client successfully with id ' . $id);
    }
}
