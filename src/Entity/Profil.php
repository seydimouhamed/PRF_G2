<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
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
 *                  "security_message"="Acces non autorisé"
 *          },
 * 
 *            "get_admin_profils_users"={ 
 *               "method"="GET", 
 *               "path"="/admin/profils/{id}/users",
 *                  "security_message"="Acces non autorisé"
 *          },
 *            "create_profil"={ 
 *               "method"="POST", 
 *               "path"="/admin/profils",
 *                  "security_message"="Acces non autorisé"
 *          }
 *      },
 *      itemOperations={
 *           "get_admin_profils_id"={ 
 *               "method"="GET", 
 *               "path"="/admin/profils/{id}",
 *                  "security_message"="Acces non autorisé"
 *          },
 * 
 *            "put_admin_profils_id"={ 
 *               "method"="PUT", 
 *               "path"="/admin/profils/{id}",
 *                  "security_message"="Acces non autorisé",
 *          },
 *            "delete_profil"={ 
 *               "method"="DELETE", 
 *               "path"="/admin/profils/{id}",
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
     * @Groups({"profil:read", "user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profil:read", "user:read"})
     * @Assert\NotBlank
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profil:read", "user:read"})
     * @Assert\NotBlank
     */
    private $abbr;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
     * @ApiSubresource
     * @Groups({"profil:read"})
     */
    private $users;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $archivage;

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

    public function getArchivage(): ?bool
    {
        return $this->archivage;
    }

    public function setArchivage(bool $archivage): self
    {
        $this->archivage = $archivage;

        return $this;
    }
}
