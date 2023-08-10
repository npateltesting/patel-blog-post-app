<?php

namespace App\Command;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Constraints\Date;

#[AsCommand(
    name: 'app:generate-posts',
    description: 'Add 100 records of posts',
)]
class GeneratePostsCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $faker = Factory::create();
        $io = new SymfonyStyle($input, $output);
        for ($i = 0; $i < 100; $i++) {
            $post = new Post();
            $post->setTitle($faker->realText(50));
            $post->setContent($faker->realText(5000));
            $post->setCreatedAt(new \DateTime());
            $this->entityManager->persist($post);
            $randomCommentCountForEachPost = rand(1, 10);
            for ($j = 0; $j < $randomCommentCountForEachPost; $j++) {
                $comment = new Comment();
                $comment->setPost($post);
                $comment->setCreatedAt(new \DateTime());
                $comment->setAuthor($faker->email);
                $comment->setText($faker->realText(150));
                $this->entityManager->persist($comment);
            }
        }
        $this->entityManager->flush();
        $io->success('You have successfully created 100 records of post with comment.');

        return Command::SUCCESS;
    }
}
