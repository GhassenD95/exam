<?php

namespace App\Controller;

use App\Entity\Aeroport;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AeroportController extends AbstractController
{
    #[Route('/aeroport', name: 'app_aeroport')]
    public function index(EntityManagerInterface $em): Response
    {

        $aeroports = $em->getRepository(Aeroport::class)->findAll();


        return $this->render('aeroport/index.html.twig', [
            'aeroports' => $aeroports,
        ]);
    }

    #[Route('/aeroport/create', name: "app_aeroport_create")]
    public function create(): Response
    {
        return $this->render("aeroport/create.html.twig");
    }

    #[Route('/aeroport/store', name: "app_aeroport_store", methods: ['post'])]
    public function store(EntityManagerInterface $em, Request $request): \Symfony\Component\HttpFoundation\RedirectResponse
    {
            $nom = $request->get("nom");
            $ville = $request->get("ville");

            if(empty($nom) || empty($ville)){
                $this->addFlash("error", "fill all fields.");
                return $this->redirectToRoute("app_aeroport_create");
            }

            $aeroport = new Aeroport();
            $aeroport->setNom($nom);
            $aeroport->setVille($ville);

            $em->persist($aeroport);
            $em->flush();

            $this->addFlash("error", "aeroport added.");
            return $this->redirectToRoute("app_aeroport");


    }

    #[Route('/aeroport/edit/{id}', name: "app_aeroport_edit")]
    public function edit($id, EntityManagerInterface $em): Response
    {
        $aeroport = $em->getRepository(Aeroport::class)->find($id);
        if(!$aeroport){
            $this->addFlash("error", "aeroport not found");
            return $this->redirectToRoute("app_aeroport");
        }


        return $this->render("aeroport/edit.html.twig", [
            'aeroport' => $aeroport
        ]);
    }
    #[Route('/aeroport/update/{id}', name: "app_aeroport_update", methods: ['post'])]
    public function update(EntityManagerInterface $em, Request $request, $id): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $nom = $request->get("nom");
        $ville = $request->get("ville");



        $aeroport = $em->getRepository(Aeroport::class)->find($id);
        if(empty($nom) || empty($ville) || !$aeroport){
            $this->addFlash("error", "fail.");
            return $this->redirectToRoute("app_aeroport_edit", ['id' => $id]);
        }

        $aeroport->setVille($ville);
        $aeroport->setNom($nom) ;

        $em->persist($aeroport);
        $em->flush();

        $this->addFlash("error", "user modified.");
        return $this->redirectToRoute("app_aeroport");
    }
}
