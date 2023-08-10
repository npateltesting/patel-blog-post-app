<?php

namespace App\Controller\Api;

use App\Service\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    private PaginationService $paginationService;

    public function __construct(PaginationService $paginationService)
    {
        $this->paginationService = $paginationService;
    }

    #[Route('/api/posts', name: 'app_api_post_list', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = $request->query->get('page', 1);
        $itemPerPage = $request->query->get('itemPerPage', 10);
        $filter = $request->query->get('searchTerm', '');
        $posts = $this->paginationService->getPaginatedDataForPost($page, $itemPerPage, $filter);
        return $this->json($posts);
    }
}
