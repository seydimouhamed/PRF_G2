<?php

namespace App\Security\Voter;

use App\Entity\Apprenants;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ApprenantsVoter extends Voter
{
    protected function supports($attribute, $Apprenant)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['LIST'])
            && $Apprenant instanceof \App\Entity\Apprenants;
    }

    protected function voteOnAttribute($attribute, $Apprenant, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'LIST':
                if ($this->security->isGranted('ROLE_ADMINISTRATEUR') || $this->security->isGranted('ROLE_FORMATEURS') ) {
                    
                    return true;
                }
            
                break;
            case 'POST_VIEW':
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}
