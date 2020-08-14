<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *      collectionOperations={
 *           "get_admin_users"={ 
 *               "method"="GET", 
 *               "path"="/admin/users",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *            "add_users"={ 
 *               "method"="POST", 
 *               "path"="/admin/users",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                 "security_message"="Acces non autorisé",
 *          },
 *           "get_apprenants"={ 
 *               "method"="GET", 
 *               "path"="/apprenants",
 *               "security"="is_granted('ROLE_FORMATEUR')",
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
 *           "get_admin_users_id"={ 
 *               "method"="GET", 
 *               "path"="/admin/users/{id}",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 * 
 *            "modifier_admin_users_id"={ 
 *               "method"="PUT", 
 *               "path"="/admin/users/{id}",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 * 
 *           "get_apprenants_id"={ 
 *               "method"="GET", 
 *               "path"="/apprenants/{id}",
 *                "security"="is_granted('ROLE_FORMATEUR')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *           "get_formateur_id"={ 
 *               "method"="GET", 
 *               "path"="/formateurs/{id}",
 *                "security"="is_granted('ROLE_FORMATEUR')",
 *                  "security_message"="Acces non autorisé",
 *          },
 * 
 *            "modifier_apprenants_id"={ 
 *               "method"="PUT", 
 *               "path"="/apprenants/{id}",
 *                "security"="is_granted('ROLE_FORMATEUR')",
 *                  "security_message"="Acces non autorisé",
 *          },
 * 
 *            "modifier_formateur_id"={ 
 *               "method"="PUT", 
 *               "path"="/apprenants/{id}",
 *                "security"="is_granted('ROLE_FORMATEUR')",
 *                  "security_message"="Acces non autorisé",
 *          }
 *      },
 *       normalizationContext={"groups"={"apprenant:read"}},
 *       denormalizationContext={"groups"={"apprenant:write"}},
 * attributes={"pagination_enabled"=true, "pagination_items_per_page"=2}
 * )
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 */
class Apprenant extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $genre;

    /**
     * @ORM\Column(type="text")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $telephone;

    /**
     * @ORM\ManyToOne(targetEntity=ProfilSortie::class, inversedBy="apprenants")
     */
    private $profilSortie;

    public function getId(): ?int
    {
        return $this->id;
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
}
