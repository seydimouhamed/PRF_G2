<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *           "get_groupe"={ 
 *               "method"="GET", 
 *               "path"="/admin/groupes",
 *               "security"="is_granted('ROLE_ADMIN')",
 *               "security_message"="Acces non autorisé",
 *          },
 *          "get_groupe_apprenants"={ 
 *               "method"="GET", 
 *               "path"="/admin/groupes/apprenants",
 *               "security"="is_granted('ROLE_ADMIN')",
 *               "security_message"="Acces non autorisé",
 *          },
 *            "add_groupe"={ 
 *               "method"="POST", 
 *               "path"="/admin/groupes",
 *               "security"="is_granted('ROLE_ADMIN')",
 *               "security_message"="Acces non autorisé",
 *          }
 *      },
 *      itemOperations={
 *           "get_groupe_id"={ 
 *               "method"="GET", 
 *               "path"="/admin/groupes/{id}",
 *                "defaults"={"id"=null},
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *            "update_groupe_id"={
 *              "method"="PUT", 
 *               "path"="/admin/groupes/{id}",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *            "delete_groupe_apprenant"={
 *              "method"="DELETE", 
 *               "path"="/admin/groupes/{id}/apprenants",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *      },
 *       normalizationContext={"groups"={"groupe:read"}},
 *       denormalizationContext={"groups"={"groupe:write"}},
 *       attributes={"pagination_enabled"=true, "pagination_items_per_page"=2}
 * )
 */
class Groupe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"groupe:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe:read", "groupe:write"})
     */
    private $nom;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"groupe:read", "groupe:write"})
     */
    private $datecreation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe:read", "groupe:write"})
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"groupe:read", "groupe:write"})
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenants::class, inversedBy="groupes")
     * @ApiSubresource
     * @Groups({"groupe:read"})
     */
    private $apprenant;

    /**
     * @ORM\ManyToMany(targetEntity=Formateurs::class, inversedBy="groupes")
     */
    private $formateur;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="groupes")
     */
    private $promotions;

    public function __construct()
    {
        $this->apprenant = new ArrayCollection();
        $this->formateur = new ArrayCollection();
        $this->promotions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function setDatecreation(?\DateTimeInterface $datecreation): self
    {
        $this->datecreation = $datecreation;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Apprenants[]
     */
    public function getApprenant(): Collection
    {
        return $this->apprenant;
    }

    public function addApprenant(Apprenants $apprenant): self
    {
        if (!$this->apprenant->contains($apprenant)) {
            $this->apprenant[] = $apprenant;
        }

        return $this;
    }

    public function removeApprenant(Apprenants $apprenant): self
    {
        if ($this->apprenant->contains($apprenant)) {
            $this->apprenant->removeElement($apprenant);
        }

        return $this;
    }

    /**
     * @return Collection|Formateurs[]
     */
    public function getFormateur(): Collection
    {
        return $this->formateur;
    }

    public function addFormateur(Formateurs $formateur): self
    {
        if (!$this->formateur->contains($formateur)) {
            $this->formateur[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateurs $formateur): self
    {
        if ($this->formateur->contains($formateur)) {
            $this->formateur->removeElement($formateur);
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
            $promotion->setGroupes($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): self
    {
        if ($this->promotions->contains($promotion)) {
            $this->promotions->removeElement($promotion);
            // set the owning side to null (unless already changed)
            if ($promotion->getGroupes() === $this) {
                $promotion->setGroupes(null);
            }
        }

        return $this;
    }
}
