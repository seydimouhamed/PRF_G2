<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *           "get_admin_profils"={ 
 *               "method"="GET", 
 *               "path"="/admin/profils",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé"
 *          },
 * 
 *            "get_admin_profils_users"={ 
 *               "method"="GET", 
 *               "path"="/admin/profils/{id}/users",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé"
 *          },
 *            "create_profil"={ 
 *               "method"="POST", 
 *               "path"="/admin/profils",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé"
 *          }
 *      },
 *      itemOperations={
 *           "get_admin_profils_id"={ 
 *               "method"="GET", 
 *               "path"="/admin/profils/{id}",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé"
 *          },
 * 
 *            "put_admin_profils_id"={ 
 *               "method"="PUT", 
 *               "path"="/admin/profils/{id}",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *            "delete_profil"={ 
 *               "method"="DELETE", 
 *               "path"="/admin/profils/{id}",
 *                "controller"="App\Controller\ProfilArchiveController",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *                  "swagger_context"={
 *                                          "summary"="archive un profil",
 *                                          "description"="Ne supprime pas un profil mais il change le status qui nous sert de corbeille",
 *                                      }
 *          },
 *      },
 *      normalizationContext={"groups"={"profil:read"}},
 * )
 */
class Profil
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user:read", "profil:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "profil:read"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Utilisateurs::class, mappedBy="profil")
     * @ApiSubresource
     * @Groups({"profil:read"})
     */
    private $utilisateur;

    public function __construct()
    {
        $this->utilisateur = new ArrayCollection();
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

    /**
     * @return Collection|Utilisateurs[]
     */
    public function getUtilisateur(): Collection
    {
        return $this->utilisateur;
    }

    public function addUtilisateur(Utilisateurs $utilisateur): self
    {
        if (!$this->utilisateur->contains($utilisateur)) {
            $this->utilisateur[] = $utilisateur;
            $utilisateur->setProfil($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateurs $utilisateur): self
    {
        if ($this->utilisateur->contains($utilisateur)) {
            $this->utilisateur->removeElement($utilisateur);
            // set the owning side to null (unless already changed)
            if ($utilisateur->getProfil() === $this) {
                $utilisateur->setProfil(null);
            }
        }

        return $this;
    }
}
