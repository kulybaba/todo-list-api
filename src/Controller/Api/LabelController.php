<?php

namespace App\Controller\Api;

use App\Entity\Label;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LabelController extends AbstractController
{
    /**
     * @Route("/api/labels/list", methods={"GET"})
     */
    public function listAction(Request $request, PaginatorInterface $paginator)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $query = $this->getDoctrine()->getRepository(Label::class)->findAllLabelsQuery();

        return $this->json([
            'labels' => $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                5
            )
        ]);
    }

    /**
     * @Route("/api/labels/{id<\d+>}/view", methods={"GET"})
     */
    public function viewAction(Label $label)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->json($label);
    }

    /**
     * @Route("/api/labels/create", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function createAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$request->getContent()) {
            throw new HttpException('400', 'Bad request');
        }

        $label = $serializer->deserialize($request->getContent(), Label::class, JsonEncoder::FORMAT);

        if (count($validator->validate($label))) {
            throw new HttpException('400', 'Bad request');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($label);
        $em->flush();

        return $this->json($label);
    }

    /**
     * @Route("/api/labels/{id<\d+>}/update", methods={"PUT"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function updateAction(Request $request, Label $label, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$request->getContent()) {
            throw new HttpException('400', 'Bad request');
        }

        if (count($validator->validate($serializer->deserialize($request->getContent(), Label::class, JsonEncoder::FORMAT)))) {
            throw new HttpException('400', 'Bad request');
        }

        $data = json_decode($request->getContent(), true);

        $label->setText($data['text']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($label);
        $em->flush();

        return $this->json($label);
    }

    /**
     * @Route("/api/labels/{id<\d+>}/delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteAction(Label $label)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $em = $this->getDoctrine()->getManager();
        $em->remove($label);
        $em->flush();

        return $this->json([
            'success' => true,
            'message' => 'Label deleted!'
        ],
        Response::HTTP_OK);
    }
}
