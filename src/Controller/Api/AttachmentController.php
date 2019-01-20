<?php

namespace App\Controller\Api;

use App\Entity\Attachment;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
}
