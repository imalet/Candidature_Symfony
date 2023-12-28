<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $manager;
    private $user;

    public function __construct(EntityManagerInterface $em, UserRepository $user)
    {
        $this->manager = $em;
        $this->user = $user;
    }

    #[Route('/storeUser', name: 'add_user', methods: ['POST'])]
    public function storeUser(Request $request): Response
    {
        // Recuperation des informations dans la requetes
        $data = json_decode($request->getContent(),true);

        $email = $data['email'];
        $password = $data['password'];

        $newUser = new User();
        $newUser->setEmail($email);
        $newUser->setPassword($password);

        $this->manager->persist($newUser);
        $this->manager->flush();

        return new JsonResponse([
            'Message' => 'Enregostrement User Ok'
        ]);
    }

    #[Route('/getUser', name: 'get_users', methods:'GET')]
    public function getAllUsers(): Response
    {
        // Recuperation des informations dans la requetes
        $users = $this->user->findAll();

        // Ici, nous recuperons les inforations des utilisateurs au format JSON
        return $this->json($users,200);
    }
}
