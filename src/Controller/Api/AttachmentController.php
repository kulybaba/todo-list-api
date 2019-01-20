<?php

namespace App\Controller\Api;

use App\Entity\Attachment;
use App\Entity\Item;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AttachmentController extends AbstractController
{
    /**
     * @Route("/api/attachments/list", methods={"GET"})
     */
    public function listAction(Request $request, PaginatorInterface $paginator)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $query = $this->getDoctrine()->getRepository(Attachment::class)->findAllAttachmentsQuery();

        return $this->json([
            'attachments' => $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                5
            )
        ]);
    }

    /**
     * @Route("/api/attachments/{id<\d+>}/view", methods={"GET"})
     */
    public function viewAction(Attachment $attachment)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->json($attachment);
    }

    /**
     * @Route("/api/todo-lists/items/{id<\d+>}/attachments/create", methods={"POST"})
     */
    public function createAction(Request $request, Item $item, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$request->getContent()) {
            throw new HttpException('400', 'Bad request');
        }

        $attachment = $serializer->deserialize($request->getContent(), Attachment::class, JsonEncoder::FORMAT);
        $attachment->setItem($item);

        if (count($validator->validate($attachment))) {
            throw new HttpException('400', 'Bad request');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($attachment);
        $em->flush();

        return $this->json($item);
    }

    /**
     * @Route("/api/todo-lists/items/{id<\d+>}/attachments/delete", methods={"DELETE"})
     */
    public function deleteAction(Item $item)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $em = $this->getDoctrine()->getManager();
        $em->remove($item->getAttachment());
        $em->flush();

        $item->setAttachment(null);

        $em = $this->getDoctrine()->getManager();
        $em->persist($item);
        $em->flush();

        return $this->json($item);
    }
}
