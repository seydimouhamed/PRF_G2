<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *           "get_referentiels"={ 
 *               "method"="GET", 
 *               "path"="/admin/referentiels",
 *               "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_CM'))",
 *               "security_message"="Acces non autorisé",
 *          },
 *           "get_referentiels_groupeCompetence"={ 
 *               "method"="GET", 
 *               "path"="/admin/referentiels/groupecompetences",
 *               "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')",
 *               "security_message"="Acces non autorisé",
 *          },
 *            "add_referentiel"={ 
 *               "method"="POST", 
 *               "path"="/admin/referentiels",
 *               "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')",
 *               "security_message"="Acces non autorisé",
 *          }
 *      },
 *      itemOperations={
 *           "get_referentiel_id"={ 
 *               "method"="GET", 
 *               "path"="/admin/referentiels/{id}",
 *                "defaults"={"id"=null},
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_CM'))",
 *                  "security_message"="Acces non autorisé",
 *          },
 *
 *            "get_referentiel_id_groupecompetence"={ 
 *               "method"="GET", 
 *               "path"="/admin/referentiels/{id1}/groupecompetences/{id2}",
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_CM'))",
 *                  "security_message"="Acces non autorisé",
 *          },
 *
 *            "update_referentiel_id"={ 
 *               "method"="PUT", 
 *               "path"="/admin/referentiels/{id}",
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_CM'))",
 *                  "security_message"="Acces non autorisé",
 *          },
 *      },
 *       normalizationContext={"groups"={"referentiel:read"}},
 *       denormalizationContext={"groups"={"referentiel:write"}},
 *       attributes={"pagination_enabled"=true, "pagination_items_per_page"=5}
 * )
 */
class Referentiel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"referentiel:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel:read", "referentiel:write"})
     */
    private $libele;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"referentiel:read", "referentiel:write"})
     */
    private $presentation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel:read", "referentiel:write"})
     */
    private $programme;

    /**
     * @ORM\Column(type="text")
     * @Groups({"referentiel:read", "referentiel:write"})
     */
    private $critereAdmission;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel:read", "referentiel:write"})
     */
    private $critereevaluation;

    /**
     * @ORM\OneToMany(targetEntity=Promotion::class, mappedBy="referentiel")
     */
    private $promotion;

    /**
     * @ORM\OneToMany(targetEntity=Promotion::class, mappedBy="referentiels")
     * @ApiSubresource
     * @Groups({"referentiel:read", "referentiel:write"})
     */
    private $groupeCompetence;

    public function __construct()
    {
        $this->promotion = new ArrayCollection();
        $this->groupeCompetence = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibele(): ?string
    {
        return $this->libele;
    }

    public function setLibele(string $libele): self
    {
        $this->libele = $libele;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(?string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getProgramme(): ?string
    {
        return $this->programme;
    }

    public function setProgramme(string $programme): self
    {
        $this->programme = $programme;

        return $this;
    }

    public function getCritereAdmission(): ?string
    {
        return $this->critereAdmission;
    }

    public function setCritereAdmission(string $critereAdmission): self
    {
        $this->critereAdmission = $critereAdmission;

        return $this;
    }

    public function getCritereevaluation(): ?string
    {
        return $this->critereevaluation;
    }

    public function setCritereevaluation(string $critereevaluation): self
    {
        $this->critereevaluation = $critereevaluation;

        return $this;
    }

    /**
     * @return Collection|Promotion[]
     */
    public function getPromotion(): Collection
    {
        return $this->promotion;
    }

    public function addPromotion(Promotion $promotion): self
    {
        if (!$this->promotion->contains($promotion)) {
            $this->promotion[] = $promotion;
            $promotion->setReferentiel($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): self
    {
        if ($this->promotion->contains($promotion)) {
            $this->promotion->removeElement($promotion);
            // set the owning side to null (unless already changed)
            if ($promotion->getReferentiel() === $this) {
                $promotion->setReferentiel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GroupeCompetence[]
     */
    public function getGroupeCompetence(): Collection
    {
        return $this->groupeCompetence;
    }

    public function addGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if (!$this->groupeCompetence->contains($groupeCompetence)) {
            $this->groupeCompetence[] = $groupeCompetence;
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if ($this->groupeCompetence->contains($groupeCompetence)) {
            $this->groupeCompetence->removeElement($groupeCompetence);
        }

        return $this;
    }
}
