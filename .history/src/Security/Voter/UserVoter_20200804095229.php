<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends Voter
{

    
    private $security;

    // public function __construct(Security $security)
    // {
    //     $this->security = $security;
    // }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
       // return in_array($attribute, ['DELETE', 'UPDATE','GET',"PUT",'POST'])
        return in_array($attribute, ['SUPPRIME', 'LIST',"ADD","MODIF"])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) 
        {
            case 'SUPPRIME':
                if ($this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
                // logic to determine if the user can EDIT
                // return true or false
                break;
            case 'LIST':
                if ($this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
                // logic to determine if the user can VIEW
                // return true or false
                break;
            case 'ADD':
                if ($this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
                    // logic to determine if the user can VIEW
                    // return true or false
                    break;
             case 'MODIF':
                if ($this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
                        // logic to determine if the user can VIEW
                        // return true or false
                        break;
        }

        return false;
    }
}
