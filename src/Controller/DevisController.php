<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Repository\DevisRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class DevisController extends AbstractController
{
    /**
    * @Route("/devis", name="devis_index", methods={"GET"})
    */
    public function index(ManagerRegistry $doctrine): Response
    {
        $devis = $doctrine
            ->getRepository(Devis::class)
            ->findAll();
  
        $data = [];
  
        foreach ($devis as $d) {
           $data[] = [
               'numdevis' => $d->getNumDevis(),
               'datedevis' => $d->getDateDevis(),
           ];
        }
        return $this->json($data);
    }
 
  
    /**
     * @Route("/devis", name="devis_new", methods={"POST"})
     */
    public function new(ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $lastDevis = $doctrine->getRepository(Devis::class)->findAll();

        $devis = new Devis();
        $devis->setNumDevis(end($lastDevis)->getNumDevis()+1);
        $devis->setDateDevis(new \DateTime());
  
        $entityManager->persist($devis);
        $entityManager->flush();
  
        return $this->json('Created new devis successfully with id ' . $devis->getNumDevis());
    }
  
    /**
     * @Route("/devis/{id}", name="devis_show", methods={"GET"})
     */
    public function show(ManagerRegistry $doctrine,DevisRepository $devisRepository, int $id): Response
    {
        $devis = $devisRepository->findOneByNumDevis($id);
  
        if (!$devis) {
  
            return $this->json('No devis found for id' . $id, 404);
        }
  
        $data =  [
            'numdevis' => $devis->getNumDevis(),
            'datedevis' => $devis->getDateDevis(),
        ];
          
        return $this->json($data);
    }
  
    /**
     * @Route("/devis/{id}", name="devis_edit", methods={"PUT"})
     */
    public function edit(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $devis = $entityManager->getRepository(devis::class)->find($id);
  
        if (!$devis) {
            return $this->json('No devis found for id' . $id, 404);
        }
  
        $devis->setName($request->request->get('name'));
        $devis->setDescription($request->request->get('description'));
        $entityManager->flush();
  
        $data =  [
            'id' => $devis->getId(),
            'name' => $devis->getName(),
            'description' => $devis->getDescription(),
        ];
          
        return $this->json($data);
    }
  
    /**
     * @Route("/devis/{id}", name="devis_delete", methods={"DELETE"})
     */
    public function delete(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $devis = $entityManager->getRepository(devis::class)->find($id);
  
        if (!$devis) {
            return $this->json('No devis found for id' . $id, 404);
        }
  
        $entityManager->remove($devis);
        $entityManager->flush();
  
        return $this->json('Deleted a devis successfully with id ' . $id);
    }
}
