<?php

namespace App\Security\Voter;

use App\Entity\Utilisateurs;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UtilisateurVoter extends Voter
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    protected function supports($attribute, $utilisateurs)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['MODIF', 'LISTBYID','DELETE'])
            && $utilisateurs instanceof \App\Entity\Utilisateurs;
    }

    protected function voteOnAttribute($attribute, $utilisateurs, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'MODIF':
                if ($this->security->isGranted('ROLE_ADMINISTRATEUR')) {
                    
                    return true;
                }
                break;
                case 'LISTBYID':
                    if ($this->security->isGranted('ROLE_ADMINISTRATEUR')) {
                        
                        return true;
                    }
                    break;
            case 'DELETE':
                if ($this->security->isGranted('ROLE_ADMINISTRATEUR')) {

                    return true;
                }
                break;
        }

        return false;
    }
}
