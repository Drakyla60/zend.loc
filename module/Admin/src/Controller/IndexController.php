<?php

declare(strict_types=1);

namespace Admin\Controller;


use Admin\Entity\Post;
use Admin\Service\Parser\ParseInterface;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Filter\StaticFilter;
use Laminas\Filter\StringTrim;
use Laminas\Form\Element\Email;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Entity\Role;
use User\Entity\Tag;
use User\Entity\User;

/**
 *
 */
class IndexController extends AbstractActionController
{
    private $sessionContainer;
    private $mongoManager;
    private $entityManager;
    private ParseInterface $parser;

    public function __construct($sessionContainer, $mongoManager, $entityManager, $parser)
    {
        $this->sessionContainer = $sessionContainer;
        $this->mongoManager = $mongoManager;
        $this->entityManager = $entityManager;
        $this->parser = $parser;
    }
    public function indexAction(): ViewModel
    {
        $user = $this->mongoManager
            ->getRepository(Post::class)->findAll();

        var_dump($user);
        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([]);
    }

    public function parseAction()
    {
        $parse = $this->parser->parse();
    }

    public function importAction()
    {
        //@TODO Винести то всьо в сервіс
        $countPosts = 10;
        for ($i = 0; ; $i = $i + $countPosts) {
            $posts = $this->mongoManager
                ->createQueryBuilder(Post::class)
                ->skip($i)
                ->limit(10)
                ->getQuery()
                ->execute()
            ->toArray()
            ;

            if (null == $posts) {
                break;
            }

            foreach ($posts as $item) {

                $post = new \User\Entity\Post();
                $post->setAuthor($this->addUser($item->getAuthor()));
                $post->setTitle($item->getTitle());
                $post->setContent($item->getContent());
                $post->setDescription(substr($item->getDescription(), 0, 200));
                $post->setStatus(\User\Entity\Post::STATUS_PUBLISHED);
                $dateCreated = $item->getDateCreated() ?: date('Y-m-d H:i:s');
                $post->setDateCreated($dateCreated);
                $post->setDateUpdated(date('Y-m-d H:i:s'));
                $post->setCountViews($item->getViews());
                $post->setImage('no-image.png');

                $this->entityManager->persist($post);

                $tags = unserialize($item->getTags());

                foreach ($tags as $tagName){
                    $tagName = StaticFilter::execute($tagName, StringTrim::class);

                    if (empty($tagName)) continue;

                    $tag = $this
                        ->entityManager
                        ->getRepository(Tag::class)
                        ->findOneByName($tagName);

                    if (null == $tag)
                        $tag = new Tag();

                    $tag->setName($tagName);
                    $tag->addPost($post);

                    $this->entityManager->persist($tag);
                    $post->addTag($tag);
                }

                $this->entityManager->flush();

                unset($post);
            }
        }

        $this->layout()->setTemplate('layout/users_layout');
        return new ViewModel([]);
    }

    private function addUser($userName): User
    {
        $email = $userName . '@localhost.local';

        $user = $this->entityManager->getRepository(User::class)
            ->findOneByEmail($email);
        if (null != $user) {
            return $user;
        }

        $user = new User();
        $user->setEmail($email);
        $user->setFullName($userName);

        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create('123456');
        $user->setPassword($passwordHash);
        $user->setStatus(1);
        $currentDate = date('Y-m-d H:i:s');
        $user->setDateCreated($currentDate);
        $user->setDateUpdated($currentDate);

        $this->assignRoles($user, [0 => 4]);


        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    private function assignRoles($user, $roleIds)
    {
        // Remove old user role(s).
        $user->getRoles()->clear();

        // Assign new role(s).
        foreach ($roleIds as $roleId) {
            $role = $this->entityManager
                ->getRepository(Role::class)
                ->find($roleId);
            if ($role==null) {
                throw new \Exception('Not found role by ID');
            }

            $user->addRole($role);
        }
    }


}
