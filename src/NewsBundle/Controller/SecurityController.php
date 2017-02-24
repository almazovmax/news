<?php
namespace NewsBundle\Controller;

use NewsBundle\Entity\User;
use NewsBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $message = $this->isFlash();

        return $this->render('NewsBundle:Page:login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'message'       => $message,
        ));
    }

    private function isFlash()
    {
        $session = new Session();
        if($session->getFlashBag()->has('Reset')){
            foreach ($session->getFlashBag()->get('Reset', array()) as $message) {
            }
        } else {
            $message = null;
        }

        return $message;
    }
}