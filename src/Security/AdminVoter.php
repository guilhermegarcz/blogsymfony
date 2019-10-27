<?php


namespace App\Security;


use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AdminVoter extends Voter
{
    const OAUTH = 'oauth2';

    protected function supports($attribute, $subject)
    {
        if(!in_array($attribute, [self::OAUTH]))
            return false;

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if(empty($_SERVER["GOOGLE_CLIENT_ID"]) || empty($_SERVER["GOOGLE_CLIENT_SECRET"]))
            return true;

        $session = new Session();

        return $session->get('oauth');

    }


}