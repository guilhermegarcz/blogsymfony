<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    /**
     * @Route("/login", name="auth_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {

        return $this->render(
            "auth/login.html.twig",
            [
                'googleauth' => (!empty($_SERVER["GOOGLE_CLIENT_ID"]) && !empty($_SERVER["GOOGLE_CLIENT_SECRET"])),
                'last_username' => $authenticationUtils->getLastUsername(),
                'error' => $authenticationUtils->getLastAuthenticationError(),
            ]
        );
    }

    /**
     * @Route("/register", name="auth_register")
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function register(UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash("notice", "User registered successfully.");

            return $this->redirectToRoute("auth_login");
        }

        return $this->render(
            "auth/register.html.twig",
            [
                'form' => $form->createView(),
            ]
        );

    }

    /**
     * @Route("/logout", name="auth_logout")
     */
    public function logout()
    {
        //handled by symfony, check security.yaml
    }

}