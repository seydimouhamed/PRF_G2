<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PromotionRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *           "get_promos"={ 
 *               "method"="GET", 
 *               "path"="/admin/promo",
 *               "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *               "security_message"="Acces non autorisé",
 *          },
 *      "get_promo_principale"={ 
 *               "method"="GET", 
 *               "path"="/admin/promo/principal",
 *               "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *               "security_message"="Acces non autorisé",
 *          },
 *      "get_promo_apprenant_attente"={ 
 *               "method"="GET", 
 *               "path"="/admin/promo/aprenants/attente",
 *               "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *               "security_message"="Acces non autorisé",
 *          },
 *            "add_promos"={ 
 *               "method"="POST", 
 *               "path"="/admin/promo",
 *               "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *               "security_message"="Acces non autorisé",
 *          }
 *      },
 *      itemOperations={
 *           "get_promo_id"={ 
 *               "method"="GET", 
 *               "path"="/admin/promos/{id}",
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') )",
 *                  "security_message"="Acces non autorisé",
 *          },
 *          "get_promo_id_principale"={ 
 *               "method"="GET", 
 *               "path"="/admin/promo/{id}/principal",
 *               "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *               "security_message"="Acces non autorisé",
 *          },
 *          "get_promo_id_referentiel"={ 
 *               "method"="GET", 
 *               "path"="/admin/promo/{id}/referentiels",
 *               "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *               "security_message"="Acces non autorisé",
 *          },
 *      "get_promo_id_apprenant_attente"={ 
 *               "method"="GET", 
 *               "path"="/admin/promo/{id}/aprenants/attente",
 *               "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *               "security_message"="Acces non autorisé",
 *          },
 *      "get_promo_id_groupe_id_apprenants"={ 
 *               "method"="GET", 
 *               "path"="/admin/promo/{id}/groupes/{id}/apprenants",
 *               "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *               "security_message"="Acces non autorisé",
 *          },
 *      "get_promo_id_formateurs"={ 
 *               "method"="GET", 
 *               "path"="/admin/promo/{id}/formateurs",
 *               "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *               "security_message"="Acces non autorisé",
 *          },
 *            "update_promo_id"={ 
 *               "method"="PUT", 
 *               "path"="/admin/promos/{id}",
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *                  "security_message"="Acces non autorisé",
 *          },
 *            "update_promo_id_apprenants"={ 
 *               "method"="PUT", 
 *               "path"="/admin/promos/{id}/apprenants",
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *                  "security_message"="Acces non autorisé",
 *          },
 *            "update_promo_id_formateurs"={ 
 *               "method"="PUT", 
 *               "path"="/admin/promos/{id}/formateurs",
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *                  "security_message"="Acces non autorisé",
 *          },
 *            "update_promo_id_groupe"={ 
 *               "method"="PUT", 
 *               "path"="/admin/promos/{id}/groupe/{id}",
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *                  "security_message"="Acces non autorisé",
 *          },
 *      },
 *       normalizationContext={"groups"={"promo:read"}},
 *       denormalizationContext={"groups"={"promo:write"}},
 *       attributes={"pagination_enabled"=true, "pagination_items_per_page"=10}
 * )
 */
class Promotion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"promo:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read","promo:write"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read","promo:write"})
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     * @Groups({"promo:read","promo:write"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read","promo:write"})
     */
    private $lieu;

    /**
     * @ORM\Column(type="date")
     * @Groups({"promo:read","promo:write"})
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     * @Groups({"promo:read","promo:write"})
     */
    private $dateFinProvisoire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"promo:read","promo:write"})
     */
    private $fabrique;

    /**
     * @ORM\Column(type="date")
     * @Groups({"promo:read","promo:write"})
     */
    private $dateFinReelle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"promo:read","promo:write"})
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity=Groupe::class, inversedBy="promotions")
     */
    private $groupes;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="promotion")
     */
    private $referentiel;

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

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFinProvisoire(): ?\DateTimeInterface
    {
        return $this->dateFinProvisoire;
    }

    public function setDateFinProvisoire(\DateTimeInterface $dateFinProvisoire): self
    {
        $this->dateFinProvisoire = $dateFinProvisoire;

        return $this;
    }

    public function getFabrique(): ?string
    {
        return $this->fabrique;
    }

    public function setFabrique(?string $fabrique): self
    {
        $this->fabrique = $fabrique;

        return $this;
    }

    public function getDateFinReelle(): ?\DateTimeInterface
    {
        return $this->dateFinReelle;
    }

    public function setDateFinReelle(\DateTimeInterface $dateFinReelle): self
    {
        $this->dateFinReelle = $dateFinReelle;

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

    public function getGroupes(): ?Groupe
    {
        return $this->groupes;
    }

    public function setGroupes(?Groupe $groupes): self
    {
        $this->groupes = $groupes;

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
}
