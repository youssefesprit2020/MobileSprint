<?php


namespace AffectationBundle\Controller;

use AffectationBundle\Entity\Demande;
use AffectationBundle\Repository\DemandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class ServiceDemandeController extends Controller
{
    public function readAction(Request $request, $tag)
    {
        //on va recuperer les demandes dans ce tableaux d objets
        $demandes = new Demande();

        if($tag == 'all')
        {
            $em = $this->getDoctrine()->getManager();

            $demandes = $em->getRepository('AffectationBundle:Demande')->findAll();

        }

        else {

            $demandes = $this->getDoctrine()
                ->getRepository(Demande::class)
                ->findDemande($tag);

        }

        //definir un objet serialiser pour normaliser notre tableaux d'objet
        $serializer = new Serializer([new ObjectNormalizer()]);


        //la normalisation retourne un tableaux des strings (formatted) pret pour etre encoder en format json
        $formatted = $serializer->normalize($demandes);

        //encoder le tableaux normalisee (formatted) en format json et lui retourner comme retour de cette methode
        return new JsonResponse($formatted);
    }

    public function newAction(Request $request)
    {
        //initialiser un objet de type demande
        $demande = new Demande();

        //rensegnier les attribut de cet objet a partir de l url
        $demande->setRemarque($request->get('remarque'));
        $demande->setEtat($request->get('etat'));
        $demande->setCas($request->get('cas'));
        $demande->setDate(\DateTime::createFromFormat('Y-m-d', $request->get('date')));

        //persister l objet sur la bdd
        $em = $this->getDoctrine()->getManager();
        $em->persist($demande);
        $em->flush();

        //definir un objet serialiser pour normaliser notre objet (demande)
        $serializer = new Serializer([new ObjectNormalizer()]);

        //la normalisation retourne un tableaux des strings (formatted) pret pour etre encoder en format json
        $formatted = $serializer->normalize($demande);

        //encoder le tableaux normalisee (formatted) en format json et lui retourner comme retour de cette methode
        return new JsonResponse($formatted);
    }

    public function editAction(Request $request, Demande $id)
    {
        //recuperer l objet selon l id passee en paramettre dans l url
        $demande=$this->getDoctrine()->getRepository(Demande::class)->find($id);

        //rensegnier les attribut de cet objet a partir de l url
        $demande->setRemarque($request->get('remarque'));
        $demande->setEtat($request->get('etat'));
        $demande->setCas($request->get('cas'));
        $demande->setDate(\DateTime::createFromFormat('Y-m-d', $request->get('date')));

        //persister l objet sur la bdd
        $em = $this->getDoctrine()->getManager();
        $em->persist($demande);
        $em->flush();

        //definir un objet serialiser pour normaliser notre objet (demande)
        $serializer = new Serializer([new ObjectNormalizer()]);

        //la normalisation retourne un tableaux des strings (formatted) pret pour etre encoder en format json
        $formatted = $serializer->normalize($demande);

        //encoder le tableaux normalisee (formatted) en format json et lui retourner comme retour de cette methode
        return new JsonResponse($formatted);

    }

    public function deleteAction(Request $request, Demande $id)
    {
        //recuperer l objet selon l id passee en paramettre dans l url
        $demande=$this->getDoctrine()->getRepository(Demande::class)->find($id);

        //definir un objet serialiser pour normaliser notre objet (demande)
        $serializer = new Serializer([new ObjectNormalizer()]);

        //la normalisation retourne un tableaux des strings (formatted) pret pour etre encoder en format json
        $formatted = $serializer->normalize($demande);

        //suppimer l objet de la bdd
        $em = $this->getDoctrine()->getManager();
        $em->remove($demande);
        $em->flush();

        //encoder le tableaux normalisee (formatted) en format json et lui retourner comme retour de cette methode
        return new JsonResponse($formatted);

    }
}