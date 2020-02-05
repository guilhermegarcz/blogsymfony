<?php


namespace App\Security;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AdminVoter extends Voter
{
    const OAUTH = 'oauth2';

    /**
     * @var ParameterBagInterface
     */
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::OAUTH])) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $google_id = $this->params->get('google_client_id');
        $google_secret = $this->params->get('google_client_secret');

        if (empty($google_id) || empty($google_secret)) {
            return true;
        }

        $session = new Session();

        return $session->get('oauth');

    }


}