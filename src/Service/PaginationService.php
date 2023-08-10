<?php

namespace App\Service;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;

class PaginationService
{
    private EntityManagerInterface $entityManager;
    private PostRepository $postRepository;

    public function __construct(EntityManagerInterface $entityManager, PostRepository $postRepository)
    {
        $this->entityManager = $entityManager;
        $this->postRepository = $postRepository;
    }

    public function getPaginatedDataForPost($page, $itemPerPage, $filter = '')
    {
        $offset = ($page - 1) * $itemPerPage;

        $totalRecords = count($this->postRepository->findAll());
        $totalPages = ceil(count($this->postRepository->findAll()) / $itemPerPage);

        $query = $this->postRepository->createQueryBuilder('p');

        if (!empty($filter)) {
            $query
                ->where('p.title LIKE :searchTerm OR p.content LIKE :searchTerm')
                ->setParameter('searchTerm', '%'. $filter . '%');
        }

        if (empty($filter)) {
            $query->setFirstResult($offset);
        }
        $query->setMaxResults($itemPerPage);

        $posts = $query->getQuery()->getResult();

        $responseArr = [];
        /** @var Post $post */
        foreach ($posts as $post) {
            $postArr = [
              'id' => $post->getId(),
              'title' => $post->getTitle(),
              'content' => $post->getContent(),
              'createdAt' => $post->getCreatedAt()
            ];

            $comments = [];

            foreach ($post->getComments() as $comment) {
                $commentArr = [
                    'id' => $comment->getId(),
                    'text' => $comment->getText(),
                    'author' => $comment->getAuthor(),
                    'createdAt' => $comment->getCreatedAt()
                ];

                $comments[] = $commentArr;
            }

            $postArr['comments'] = $comments;
            $responseArr[] = $postArr;
        }

        return [
            'totalPages' => $totalPages,
            'totalRecords' => $totalRecords,
            'totalRecordsInCurrentPage' => count($responseArr),
            'currentPage' => $page,
            'date' => $responseArr,
        ];
    }
}
