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
 * 
 * @ApiResource(
 *      collectionOperations={
 *           "get_admin_profils"={ 
 *               "method"="GET", 
 *               "path"="/admin/profils",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *                  "normalization_context"={"groups":"profil:read"} 
 *          },
 * 
 *            "get_admin_profils_users"={ 
 *               "method"="GET", 
 *               "path"="/admin/profils/{id}/users",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *                  "normalization_context"={"groups":"profil:read"} 
 *          },
 *            "create_profil"={ 
 *               "method"="POST", 
 *               "path"="/admin/profils",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *                  "normalization_context"={"groups":"profil:read"} 
 *          }
 *      },
 *      itemOperations={
 *           "get_admin_profils_id"={ 
 *               "method"="GET", 
 *               "path"="/admin/profils/{id}",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *                  "normalization_context"={"groups":"profil:read"} 
 *          },
 * 
 *            "put_admin_profils_id"={ 
 *               "method"="PUT", 
 *               "path"="/admin/profils/{id}",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *                  "normalization_context"={"groups":"profil:read"} 
 *          },
 *            "delete_profil"={ 
 *               "method"="DELETE", 
 *               "path"="/admin/profils/{id}",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *                  "normalization_context"={"groups":"profil:read"} 
 *          }
 *      },
 *      attributes={"pagination_enabled"=true, "pagination_items_per_page"=1},
 * )
 */
class Profil
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"profil:read", "user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profil:read", "user:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profil:read", "user:read"})
     */
    private $abbr;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
     * @ApiSubresource
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    public function getAbbr(): ?string
    {
        return $this->abbr;
    }

    public function setAbbr(string $abbr): self
    {
        $this->abbr = $abbr;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setProfil($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getProfil() === $this) {
                $user->setProfil(null);
            }
        }

        return $this;
    }
}
