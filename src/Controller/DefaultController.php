<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Evenement;
use App\Entity\Image;
use App\Entity\Menu;
use App\Entity\Produit;
use App\Entity\Promotion;
use App\Entity\Utilisateur;
use App\Form\CommandeType;
use App\Form\ImageType;
use App\Form\PanierType;
use App\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Positive;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        $date = date('Y-m-d');

        $menu = $this->getDoctrine()->getRepository(Menu::class)->findOneByDate($date);
        $promotions = $this->getDoctrine()->getRepository(Promotion::class)->findRecent($date);
        $evenements = $this->getDoctrine()->getRepository(Evenement::class)->findRecent($date);

        return $this->render('default/index.html.twig', [
            'menu' => $menu,
            'promotions' => $promotions,
            'evenements' => $evenements
        ]);
    }

    /**
     * @Route("/evenements", name="evenements")
     */
    public function evenements(): Response
    {
        $evenements = $this->getDoctrine()->getRepository(Evenement::class)->findAll();

        return $this->render('default/evenements.html.twig', [
            'evenements' => $evenements
        ]);
    }

    /**
     * @Route("/promotions", name="promotions")
     */
    public function promotions(): Response
    {
        $promotions = $this->getDoctrine()->getRepository(Promotion::class)->findAll();

        return $this->render('default/promotions.html.twig', [
            'promotions' => $promotions
        ]);
    }

    /**
     * @Route("/produits/{type}", requirements={"type": "[a-z]+"}, defaults={"type": ""}, name="produits")
     * @param $type
     * @return Response
     */
    public function produits($type): Response
    {
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findByType($type);

        return $this->render('default/produits.html.twig', ['produits' => $produits]);
    }

    /**
     * @Route("/produits/menu/{date}", requirements={"date": "[0-9]{4}-[0-9]{2}-[0-9]{2}"}, defaults={"date": ""}, name="menu")
     * @param $date
     * @return Response
     */
    public function menu($date): Response
    {
        $produits = $this->getDoctrine()->getRepository(Menu::class)->findOneByDate($date)->getComposition();

        return $this->render('default/produits.html.twig', ['produits' => $produits]);
    }

    /**
     * @Route("/produit/{id}", requirements={"id": "[1-9]\d*"}, defaults={"id": ""}, name="produit")
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function produit($id, Request $request): Response
    {
        $produit = $this->getDoctrine()->getRepository(Produit::class)->findOneById($id);
        $currentUser =  $this->getUser();
        $commande = new Commande();

        $commande->setProduit($produit);
        $commande->setUtilisateur($currentUser);
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();
            return $this->redirectToRoute('produits');
        }

        return $this->render('default/produit.html.twig', ['produit' => $produit, 'form' => $form->createView()]);
    }

    /**
     * @Route("/panier", name="panier")
     */
    public function panier(Request $request): Response
    {
        $currentUser = $this->getUser();
        $commandes = $this->getDoctrine()->getRepository(Commande::class)->findByUtilisateur($currentUser);
        $forms = array();

        foreach($commandes as $commande) {
            $form = $this->createFormBuilder($commande)
                ->add('quantite', IntegerType::class, array(
                    'attr' => array('min' => 1, 'max' => $commande->getQuantite(), 'value' => 1),
                    'constraints' => array(new Positive()),
                    'mapped' => false
                ))
                ->add('Supprimer', SubmitType::class)
                ->getForm()
            ;
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $quantite = $commande->getQuantite() - $form->get('quantite')->getData();
                if ($quantite > 0) {
                    $commande->setQuantite($quantite);
                } else {
                    $em->remove($commande);
                }
                $em->flush();
                return $this->redirectToRoute('panier');
            }
            $forms[] = $form->createView();
        }

        return $this->render('default/panier.html.twig', [
            'commandes' => $commandes,
            'forms' => $forms
        ]);
    }


    /**
     * @Route("/admin/image-upload", name="image-upload")
     * @param Request $request
     * @return Response
     */
    public function imageUpload(Request $request): Response
    {
        $img = new Image();

        $form = $this->createForm(ImageType::class, $img);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($img);
            $em->flush();
            return $this->redirectToRoute('image-upload');
        }
        return $this->render('default/image-upload.html.twig', ['form' => $form->createView()]);
    }
}
