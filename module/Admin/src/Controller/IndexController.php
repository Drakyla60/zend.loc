<?php

declare(strict_types=1);

namespace Admin\Controller;


use Admin\Entity\Post;
use Admin\Entity\Product;
use Admin\Service\ParseInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

/**
 *
 */
class IndexController extends AbstractActionController
{
    private $mongoManager;
    private $sessionContainer;
    private ParseInterface $parser;

    public function __construct($sessionContainer, $mongoManager, $parser)
    {
        $this->mongoManager = $mongoManager;
        $this->sessionContainer = $sessionContainer;
        $this->parser = $parser;
    }
    public function indexAction(): ViewModel
    {

        $parse = $this->parser->parse();

        foreach ($parse as $item) {
            $post = new Post();
            $post->setTitle($item['postTitle']);
            $post->setAuthor($item['postAuthor']);
            $post->setDescription(iconv("UTF-8","UTF-8//IGNORE",substr($item['postContent'], 0, 200)));
            $post->setContent(iconv("UTF-8","UTF-8//IGNORE",$item['postContent']));
            $post->setTags(serialize($item['postTags']));
            $post->setRating($item['postRating']);
            $post->setViews($item['postViews']);

            $this->mongoManager->persist($post);

            $this->mongoManager->flush();
        }

        $user = $this->mongoManager
            ->getRepository(Post::class)->findAll();

        var_dump($user);

        return new ViewModel([]);
    }


}
