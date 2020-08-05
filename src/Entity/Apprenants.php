<?php

namespace App\Entity;

use App\Entity\Utilisateurs;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantsRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
  * @ApiResource(
 *      collectionOperations={
 *           "get_apprenants"={ 
 *               "method"="GET", 
 *               "path"="/apprenants",
 *               "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_CM'))",
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
 *           "get_apprenants_id"={ 
 *               "method"="GET", 
 *               "path"="/apprenants/{id}",
 *                "defaults"={"id"=null},
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_APPRENANT') or is_granted('ROLE_CM'))",
 *                  "security_message"="Acces non autorisé",
 *          },
 *
 *            "modifier_apprenants_id"={ 
 *               "method"="PUT", 
 *               "path"="/apprenants/{id}",
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_APPRENANT'))",
 *                  "security_message"="Acces non autorisé",
 *          },
 *
 *            "archiver_apprenants_id"={ 
 *               "method"="DELETE", 
 *               "path"="/apprenants/{id}",
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *                  "security_message"="Acces non autorisé",
 *          },
 *      },
 *       normalizationContext={"groups"={"apprenants:read","user:read"}},
 *       denormalizationContext={"groups"={"apprenants:write","user:write"}}
 * )
 * @ORM\Entity(repositoryClass=ApprenantsRepository::class)
 */
class Apprenants extends Utilisateurs
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"apprenants:read", "apprenants:write"})
     */
    private $genre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"apprenants:read", "apprenants:write"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"apprenants:read", "apprenants:write"})
     */
    private $telephone;

    /**
     * @ORM\ManyToOne(targetEntity=ProfilSorti::class, inversedBy="apprenant")
     * @Groups({"apprenants:read"})
     *  @ApiSubresource
     */
    private $profilSorti;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="apprenant")
     */
    private $groupes;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
    }

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

    public function getProfilSorti(): ?ProfilSorti
    {
        return $this->profilSorti;
    }

    public function setProfilSorti(?ProfilSorti $profilSorti): self
    {
        $this->profilSorti = $profilSorti;

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->addApprenant($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->contains($groupe)) {
            $this->groupes->removeElement($groupe);
            $groupe->removeApprenant($this);
        }

        return $this;
    }
}
