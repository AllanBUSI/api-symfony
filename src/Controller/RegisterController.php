<?php 

// src/Controller/RegisterController.php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;




class RegisterController extends AbstractController
{
    #[Route('/register', methods: ['POST'])]
    public function Response(Request $request, EntityManagerInterface $entityManager): Response
    {
        // j'appel mes données
        $firstname = $request->request->get('firstname');
        $lastname = $request->request->get('lastname');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $confirmPassword = $request->request->get('confirmPassword');

        // je traite mes données
        $user = $entityManager->getRepository(User::class);

        $error = $user->save([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => $password,
            'confirmPassword' => $confirmPassword,
        ], []);

        var_dump($error);

        // j'enregistre mes données
        $response = new Response();
        // si les password sont different -> error sinon ok

        if ($password != $confirmPassword) {
            $response->setContent(json_encode([
                'data' => [
                    'titleError' => 'Erreur de password',
                    'error' => 'Vos mot de passe ne sont pas identique',
                ],
            ]));
            $response->setStatusCode(409);
        } else {
            $response->setContent(json_encode([
                'data' => [
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $email,
                    'password' => $password,
                    'confirmPassword' => $confirmPassword
                ],
            ]));
            $response->setStatusCode(200);
        }

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}