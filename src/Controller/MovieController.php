<?php


namespace App\Controller;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movie")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/", name="oc_advert_getAll", methods={"GET"})
     */
    public function getAll() {
        $em = $this->getDoctrine()
            ->getManager();
        $repository = $em->getRepository('App\Entity\Movie');

        $movies = $repository->findAll();
        $data = [];

        foreach ($movies as $m) {
            $data[] = [
                'id' => $m->getId(),
                'titre' => $m->getTitre(),
                'img' => $m->getImg(),
                'liked' => $m->getLiked(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/add", name="oc_advert_add", methods={"POST"})
     */
    public function add(Request $request) {
        $data = json_decode($request->getContent(), true);
        $movie = new Movie();
        $movie->setTitre($data['titre']);
        $movie->setImg($data['img']);
        $movie->setLiked($data['liked']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($movie);
        $em->flush();


        return new JsonResponse(['status' => 'movie created!'], Response::HTTP_OK);
    }

    /**
     * @Route("/toggleLike", name="oc_advert_edit", methods={"PUT"})
     */
    public function edit(Request $request) {
        $data = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()
            ->getManager();
        $repository = $em->getRepository('App\Entity\Movie');

        $movie = $repository->find($data['id']);
        $movie->setLiked($data['liked']);
        $em->persist($movie);
        $em->flush();


        return new JsonResponse(['status' => 'movie updated!'], Response::HTTP_OK);
    }
}