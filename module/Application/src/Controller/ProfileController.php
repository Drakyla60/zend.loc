<?php


namespace Application\Controller;


use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use User\Entity\User;

class ProfileController extends AbstractActionController
{
    private $authService;
    private $entityManager;

    public function __construct($authService, $entityManager)
    {
        $this->authService = $authService;
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $email = $this->authService->getIdentity();

        $profile = $this->entityManager
            ->getRepository(User::class)->findOneBy(['email' => $email]);

        return new ViewModel([
            'profile' => $profile,
        ]);
    }

    public function getJsonAction()
    {
        return new JsonModel([
            'status' => 'SUCCESS',
            'message'=>'Here is your data',
            'data' => [
                'full_name' => 'John Doe',
                'address' => '51 Middle st.'
            ]
        ]);
    }


}