<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PromotionRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *           "get_promo"={
 *               "method"="GET",
 *               "path"="/admin/promo",
 *               "security"="is_granted('ROLE_ADMIN')",
 *               "security_message"="Acces non autorisé",
 *          },
 *     "get_promo_principal"={
 *               "method"="GET",
 *               "path"="/admin/promo/principal",
 *               "security"="is_granted('ROLE_ADMIN')",
 *               "security_message"="Acces non autorisé",
 *          },
 *     "get_promo_apprenants_attente"={
 *               "method"="GET",
 *               "path"="/admin/promo/apprenants/attente",
 *               "security"="is_granted('ROLE_ADMIN')",
 *               "security_message"="Acces non autorisé",
 *          },
 *            "add_promo"={
 *               "method"="POST",
 *               "path"="/admin/promo",
 *               "security"="is_granted('ROLE_ADMIN')",
 *               "security_message"="Acces non autorisé",
 *          }
 *      },
 *      itemOperations={
 *           "get_promo_id"={
 *               "method"="GET",
 *               "path"="/admin/promo/{id}",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *           "get_promo_id_principal"={
 *               "method"="GET",
 *               "path"="/admin/promo/{id}/principal",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *           "get_promo_id_referentiel"={
 *               "method"="GET",
 *               "path"="/admin/promo/{id}/referentiels",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *           "get_promo_id_apprenants_attente"={
 *               "method"="GET",
 *               "path"="/admin/promo/{id}/apprenants/attente",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *           "get_promo_id_groupe_id_apprenants"={
 *               "method"="GET",
 *               "path"="/admin/promo/{id1}/groupes/{id2}/apprenants",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *           "get_promo_id_formateurs"={
 *               "method"="GET",
 *               "path"="/admin/promo/{id}/formateurs",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *            "update_promo_id"={
 *               "method"="PUT",
 *               "path"="/admin/promo/{id}",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *            "update_promo_id_apprenants"={
 *               "method"="PUT",
 *               "path"="/admin/promo/{id}/apprenants",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *            "update_promo_id_formateurs"={
 *               "method"="PUT",
 *               "path"="/admin/promo/{id}/formateurs",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *            "update_promo_id_groupes_id"={
 *               "method"="PUT",
 *               "path"="/admin/promo/{id1}/groupes/{id2}",
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
 *       normalizationContext={"groups"={"promotion:read"}},
 *       denormalizationContext={"groups"={"promotion:write"}},
 *       attributes={"pagination_enabled"=true, "pagination_items_per_page"=10}
 * )
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 */
class Promotion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"promotion:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promotion:read","promotion:write"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promotion:read","promotion:write"})
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     * @Groups({"promotion:read","promotion:write"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promotion:read","promotion:write"})
     */
    private $lieu;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"promotion:read","promotion:write"})
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"promotion:read","promotion:write"})
     */
    private $dateFinPrvisoire;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promotion:read","promotion:write"})
     */
    private $fabrique;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"promotion:read","promotion:write"})
     */
    private $dateFinReelle;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"promotion:read","promotion:write"})
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="promotions")
     * @ApiSubresource
     * @Groups({"promotion:read"})
     */
    private $referentiel;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="promotions")
     * @ApiSubresource
     * @Groups({"promotion:read"})
     */
    private $formateurs;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="promotions")
     * @ApiSubresource
     * @Groups({"promotion:read"})
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Groupes::class, mappedBy="promotions")
     * @ApiSubresource
     * @Groups({"promotion:read"})
     */
    private $groupe;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->groupe = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFinPrvisoire(): ?\DateTimeInterface
    {
        return $this->dateFinPrvisoire;
    }

    public function setDateFinPrvisoire(?\DateTimeInterface $dateFinPrvisoire): self
    {
        $this->dateFinPrvisoire = $dateFinPrvisoire;

        return $this;
    }

    public function getFabrique(): ?string
    {
        return $this->fabrique;
    }

    public function setFabrique(string $fabrique): self
    {
        $this->fabrique = $fabrique;

        return $this;
    }

    public function getDateFinReelle(): ?\DateTimeInterface
    {
        return $this->dateFinReelle;
    }

    public function setDateFinReelle(?\DateTimeInterface $dateFinReelle): self
    {
        $this->dateFinReelle = $dateFinReelle;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getReferentiel(): ?Referentiel
    {
        return $this->referentiel;
    }

    public function setReferentiel(?Referentiel $referentiel): self
    {
        $this->referentiel = $referentiel;

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

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return Collection|Groupes[]
     */
    public function getGroupe(): Collection
    {
        return $this->groupe;
    }

    public function addGroupe(Groupes $groupe): self
    {
        if (!$this->groupe->contains($groupe)) {
            $this->groupe[] = $groupe;
            $groupe->setPromotions($this);
        }

        return $this;
    }

    public function removeGroupe(Groupes $groupe): self
    {
        if ($this->groupe->contains($groupe)) {
            $this->groupe->removeElement($groupe);
            // set the owning side to null (unless already changed)
            if ($groupe->getPromotions() === $this) {
                $groupe->setPromotions(null);
            }
        }

        return $this;
    }
}
