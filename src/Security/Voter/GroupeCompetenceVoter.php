<?php

namespace App\Security\Voter;

use App\Entity\GroupeCompetence;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class GroupeCompetenceVoter extends Voter
{

    private $request;
    private $gc_repo;

    public function __construct(RequestStack $request,GroupeCompetenceRepository $gc_repo)
    {
        $this->request = $request;
        $this->gc_repo = $gc_repo;
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['GC_EDIT', 'GC_VIEW'])
            && $subject instanceof \App\Entity\GroupeCompetence;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
       // $req=$this->request->getCurrentRequest()->getContent();
       $req=$user->getID();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'GC_EDIT':
                   dd($req);
                    return false;
                break;
            case 'GC_VIEW':
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}
