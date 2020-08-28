<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      collectionOperations={
 *           "get_apprenants"={ 
 *               "method"="GET", 
 *               "path"="/apprenants",
 *               "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_CM'))",
 *               "security_message"="Acces non autorisé",
 *          },
 *            "add_apprenant"={ 
 *               "method"="POST", 
 *               "path"="/apprenants",
 *               "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *               "security_message"="Acces non autorisé",
 *          }
 *      },
 *      itemOperations={
 *           "get_apprenants_id"={ 
 *               "method"="GET", 
 *               "path"="/apprenants/{id}",
 *                "defaults"={"id"=null},
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_APPRENANT') or is_granted('ROLE_CM'))",
 *                  "security_message"="Acces non autorisé",
 *          },
 *
 *            "modifier_apprenants_id"={ 
 *               "method"="PUT", 
 *               "path"="/apprenants/{id}",
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_APPRENANT'))",
 *                  "security_message"="Acces non autorisé",
 *          },
 *
 *            "archiver_apprenants_id"={ 
 *               "method"="DELETE", 
 *               "path"="/apprenants/{id}",
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *                  "security_message"="Acces non autorisé",
 *          },
 *      },
 *       normalizationContext={"groups"={"apprenant:read","user:read"}},
 *       denormalizationContext={"groups"={"apprenant:write","user:write"}}
 *
 * )
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 */
class Apprenant extends User
{

    /**
     * @ORM\Column(type="string", length=30)
     * @Groups({"apprenant:read"})
     * @Groups({"groupe:read"})
     * @Groups({"promo:read"})
     * @Groups({"apprenant:read", "apprenant:write","promo:read"})
     * @Groups({"getBriefByOneGroupeApp"})
     *
     */
    private $genre;

    /**
     * @ORM\Column(type="text")
     * @Groups({"apprenant:read"})
     * @Groups({"groupe:read"})
     * @Groups({"promo:read"})
     * @Groups({"apprenant:read", "apprenant:write","promo:read"})
     * @Groups({"getBriefByOneGroupeApp"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"apprenant:read"})
     * @Groups({"groupe:read"})
     * @Groups({"promo:read"})
     * @Groups({"apprenant:read", "apprenant:write", "promo:read"})
     * @Groups({"getBriefByOneGroupeApp"})
     */
    private $telephone;

    /**
     * @ORM\ManyToOne(targetEntity=ProfilSortie::class, inversedBy="apprenants")
     * @Groups({"getBriefByOneGroupeApp"})
     *  @ApiSubresource()
     */
    private $profilSortie;

    /**
     * @ORM\ManyToMany(targetEntity=Groupes::class, inversedBy="apprenants")
     */
    private $groupes;

    /**
     * @ORM\Column(type="string", length=50,options={"default" : "attente"})
     * @Groups({"apprenant:read", "apprenant:write", "groupe:read"})
     */
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity=CompetencesValide::class, mappedBy="apprenant")
     */
    private $competencesValides;

    /**
     * @ORM\OneToMany(targetEntity=BriefApprenant::class, mappedBy="apprenant")
     */
    private $briefApprenants;

    /**
     * @ORM\OneToMany(targetEntity=AprenantLivrablePartiel::class, mappedBy="apprenant")
     */
    private $aprenantLivrablePartiels;

    /**
     * @ORM\OneToMany(targetEntity=LivrableAttenduApprenant::class, mappedBy="apprenant")
     */
    private $livrableAttenduApprenants;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->competencesValides = new ArrayCollection();
        $this->briefApprenants = new ArrayCollection();
        $this->aprenantLivrablePartiels = new ArrayCollection();
        $this->livrableAttenduApprenants = new ArrayCollection();
    }

   

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getProfilSortie(): ?ProfilSortie
    {
        return $this->profilSortie;
    }

    public function setProfilSortie(?ProfilSortie $profilSortie): self
    {
        $this->profilSortie = $profilSortie;

        return $this;
    }

    /**
     * @return Collection|Groupes[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupes $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
        }

        return $this;
    }

    public function removeGroupe(Groupes $groupe): self
    {
        if ($this->groupes->contains($groupe)) {
            $this->groupes->removeElement($groupe);
        }

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    
    /**
     * @return Collection|CompetencesValide[]
     */
    public function getCompetencesValides(): Collection
    {
        return $this->competencesValides;
    }

    public function addCompetencesValide(CompetencesValide $competencesValide): self
    {
        if (!$this->competencesValides->contains($competencesValide)) {
            $this->competencesValides[] = $competencesValide;
            $competencesValide->setApprenant($this);
        }

        return $this;
    }

    public function removeCompetencesValide(CompetencesValide $competencesValide): self
    {
        if ($this->competencesValides->contains($competencesValide)) {
            $this->competencesValides->removeElement($competencesValide);
            // set the owning side to null (unless already changed)
            if ($competencesValide->getApprenant() === $this) {
                $competencesValide->setApprenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BriefApprenant[]
     */
    public function getBriefApprenants(): Collection
    {
        return $this->briefApprenants;
    }

    public function addBriefApprenant(BriefApprenant $briefApprenant): self
    {
        if (!$this->briefApprenants->contains($briefApprenant)) {
            $this->briefApprenants[] = $briefApprenant;
            $briefApprenant->setApprenant($this);
        }

        return $this;
    }

    public function removeBriefApprenant(BriefApprenant $briefApprenant): self
    {
        if ($this->briefApprenants->contains($briefApprenant)) {
            $this->briefApprenants->removeElement($briefApprenant);
            // set the owning side to null (unless already changed)
            if ($briefApprenant->getApprenant() === $this) {
                $briefApprenant->setApprenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AprenantLivrablePartiel[]
     */
    public function getAprenantLivrablePartiels(): Collection
    {
        return $this->aprenantLivrablePartiels;
    }

    public function addAprenantLivrablePartiel(AprenantLivrablePartiel $aprenantLivrablePartiel): self
    {
        if (!$this->aprenantLivrablePartiels->contains($aprenantLivrablePartiel)) {
            $this->aprenantLivrablePartiels[] = $aprenantLivrablePartiel;
            $aprenantLivrablePartiel->setApprenant($this);
        }

        return $this;
    }

    public function removeAprenantLivrablePartiel(AprenantLivrablePartiel $aprenantLivrablePartiel): self
    {
        if ($this->aprenantLivrablePartiels->contains($aprenantLivrablePartiel)) {
            $this->aprenantLivrablePartiels->removeElement($aprenantLivrablePartiel);
            // set the owning side to null (unless already changed)
            if ($aprenantLivrablePartiel->getApprenant() === $this) {
                $aprenantLivrablePartiel->setApprenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LivrableAttenduApprenant[]
     */
    public function getLivrableAttenduApprenants(): Collection
    {
        return $this->livrableAttenduApprenants;
    }

    public function addLivrableAttenduApprenant(LivrableAttenduApprenant $livrableAttenduApprenant): self
    {
        if (!$this->livrableAttenduApprenants->contains($livrableAttenduApprenant)) {
            $this->livrableAttenduApprenants[] = $livrableAttenduApprenant;
            $livrableAttenduApprenant->setApprenant($this);
        }

        return $this;
    }

    public function removeLivrableAttenduApprenant(LivrableAttenduApprenant $livrableAttenduApprenant): self
    {
        if ($this->livrableAttenduApprenants->contains($livrableAttenduApprenant)) {
            $this->livrableAttenduApprenants->removeElement($livrableAttenduApprenant);
            // set the owning side to null (unless already changed)
            if ($livrableAttenduApprenant->getApprenant() === $this) {
                $livrableAttenduApprenant->setApprenant(null);
            }
        }

        return $this;
    }
}
