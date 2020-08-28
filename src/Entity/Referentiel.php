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
 * @ApiResource(
 *      collectionOperations={
 *           "get_referentiels"={ 
 *               "method"="GET", 
 *               "path"="/admin/referentiels",
 *               "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_CM'))",
 *               "security_message"="Acces non autorisé",
 *          },
 *           "get_referentiels_grpCompetence"={ 
 *               "method"="GET", 
 *               "path"="/admin/referentiels/grpecompetences",
 *                "route_name"="get_grpcompetence_competence",
 *               "security"="is_granted('ROLE_ADMIN')",
 *               "get_grpcompetence_discontinuation",
 *          },
 *            "add_referentiel"={ 
 *               "method"="POST", 
 *               "path"="/admin/referentiels",
 *               "security"="is_granted('ROLE_ADMIN')",
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
 *            "get_referentiel_id_grpcompetence"={ 
 *               "method"="GET", 
 *               "path"="/admin/referentiels/{id1}/gprecompetences/{id2}",
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
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 */
class Referentiel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"referentiel:read","brief:read"})
     * @Groups({"promo:read"})
     * @Groups({"getBriefByOneGroupe","getOnBriefOnePromo"})
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel:read","brief:read"})
     * @Groups({"promo:read", "promo:write"})
     *@Groups({"getBriefByOneGroupe","getOnBriefOnePromo"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     *@Groups({"referentiel:read","brief:read"})
     * @Groups({"promo:read", "promo:write"})
     *@Groups({"getBriefByOneGroupe","getOnBriefOnePromo"})
     */
    private $presentation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel:read","brief:read"})
     * @Groups({"promo:read", "promo:write"})
     *@Groups({"getBriefByOneGroupe","getOnBriefOnePromo"})
     */
    private $programme;

    /**
     * @ORM\Column(type="text")
     * @Groups({"referentiel:read","brief:read"})
     * @Groups({"promo:read", "promo:write"})
     *@Groups({"getBriefByOneGroupe","getOnBriefOnePromo"})
     */
    private $critereAdmission;

    /**
     * @ORM\Column(type="text")
     * @Groups({"referentiel:read","brief:read"})
     * @Groups({"promo:read", "promo:write"})
     * @Groups({"getBriefByOneGroupe","getOnBriefOnePromo"})
     */
    private $critereEvaluation;


    /**
     * @ORM\OneToMany(targetEntity=Promotion::class, mappedBy="referentiel")
     * @ApiSubresource()
     * @Groups({"referentiel:read"})
     *
     */
    private $promotions;


    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, inversedBy="referentiels")
     * @ApiSubresource
     * @Groups({"referentiel:read", "referentiel:write"})
     */
    private $grpCompetences;

    /**
     * @ORM\OneToMany(targetEntity=Brief::class, mappedBy="referentiel")
     */
    private $briefs;

    public function __construct()
    {
         $this->grpCompetences = new ArrayCollection();
         $this->promotions = new ArrayCollection();
         $this->briefs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
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

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

        return $this;
    }


    /**
     * @return Collection|GroupeCompetence[]
     */
    public function getGrpCompetences(): Collection
    {
        return $this->grpCompetences;
    }

    public function addGrpCompetence(GroupeCompetence $grpCompetence): self
    {
        if (!$this->grpCompetences->contains($grpCompetence)) {
            $this->grpCompetences[] = $grpCompetence;
        }

        return $this;
    }

    public function removeGrpCompetence(GroupeCompetence $grpCompetence): self
    {
        if ($this->grpCompetences->contains($grpCompetence)) {
            $this->grpCompetences->removeElement($grpCompetence);
        }

        return $this;
    }

    /**
     * @return Collection|Promotion[]
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): self
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions[] = $promotion;
            $promotion->setReferentiel($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): self
    {
        if ($this->promotions->contains($promotion)) {
            $this->promotions->removeElement($promotion);
            // set the owning side to null (unless already changed)
            if ($promotion->getReferentiel() === $this) {
                $promotion->setReferentiel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Brief[]
     */
    public function getBriefs(): Collection
    {
        return $this->briefs;
    }

    public function addBrief(Brief $brief): self
    {
        if (!$this->briefs->contains($brief)) {
            $this->briefs[] = $brief;
            $brief->setReferentiel($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->contains($brief)) {
            $this->briefs->removeElement($brief);
            // set the owning side to null (unless already changed)
            if ($brief->getReferentiel() === $this) {
                $brief->setReferentiel(null);
            }
        }

        return $this;
    }

}
