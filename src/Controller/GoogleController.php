<?php

namespace App\Controller;


use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GoogleController extends AbstractController
{
    /**
     * @Route("/connect/google", name="connect_google")
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        if (!empty($_SERVER["GOOGLE_CLIENT_ID"]) && !empty($_SERVER["GOOGLE_CLIENT_SECRET"])) {
            return $clientRegistry
                ->getClient('google')
                ->redirect();
        }

        return $this->redirectToRoute("auth_login");
    }

    /**
     * @Route("/connect/google/check", name="connect_google_check")
     */
    public function connectCheckAction(Request $request)
    {
        if (!$this->getUser()) {
            $this->addFlash('error', "User was not found");

            return $this->redirectToRoute('auth_login');
        } else {
            return $this->redirectToRoute('article_index');
        }

    }

}
