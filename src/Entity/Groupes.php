<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     collectionOperations={
 *           "get_groupe"={
 *               "method"="GET",
 *               "path"="/admin/groupes",
 *               "security"="is_granted('ROLE_ADMIN')",
 *               "security_message"="Acces non autorisé",
 *          },
     *     "get_groupe_apprenants"={
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
 *               "method"="PUT",
 *               "path"="/admin/groupes/{id}",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *            "delete_groupe_id_apprenants"={
 *               "method"="DELETE",
 *               "path"="/admin/groupes/{id}/apprenants",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *      },
 *       normalizationContext={"groups"={"groupe:read"}},
 *       denormalizationContext={"groups"={"groupe:write"}},
 *       attributes={"pagination_enabled"=true, "pagination_items_per_page"=10}
 * )
 * @ORM\Entity(repositoryClass=GroupesRepository::class)
 */
class Groupes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"groupe:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"groupe:read","groupe:write"})
     */
    private $nom;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"groupe:read","groupe:write"})
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"groupe:read","groupe:write"})
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"groupe:read","groupe:write"})
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Promotion::class, inversedBy="groupe")
     * @ApiSubresource
     * @Groups({"groupe:read"})
     */
    private $promotions;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="groupe")
     * @ApiSubresource
     * @Groups({"groupe:read"})
     */
    private $apprenants;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="groupe")
     * @ApiSubresource
     * @Groups({"groupe:read"})
     */
    private $formateurs;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
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

    public function getPromotions(): ?Promotion
    {
        return $this->promotions;
    }

    public function setPromotions(?Promotion $promotions): self
    {
        $this->promotions = $promotions;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->contains($apprenant)) {
            $this->apprenants->removeElement($apprenant);
        }

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        if ($this->formateurs->contains($formateur)) {
            $this->formateurs->removeElement($formateur);
        }

        return $this;
    }
}
