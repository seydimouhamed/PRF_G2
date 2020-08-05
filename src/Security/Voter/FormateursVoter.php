<?php

namespace App\Security\Voter;


use App\Entity\Utilisateurs;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class FormateursVoter extends Voter
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    protected function supports($attribute, $Formateurs)
    {

        
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['LISTById','LIST','MODIF'])
            && $Formateurs instanceof \App\Entity\Utilisateurs;
    }

    protected function voteOnAttribute($attribute, $Formateurs, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'LISTById':
                if ($user->getRoles()[0]=="ROLE_ADMINISTRATEUR" || $user->getRoles()[0]=="ROLE_FORMATEURS" ) {
                    
                    return dd($Formateurs->$user->getId());
                }
                break;
                case 'LIST':
                    if ($user->getRoles()[0]=="ROLE_ADMINISTRATEUR") {
                        
                        return true;
                    }
                    break;
                case 'MODIF':
                    if ($this->security->isGranted('ROLE_ADMINISTRATEUR')) {
                        
                        return true;
                    }
                    break;
        }

        return false;
    }
}
