<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilSortiRepository;
use App\Controller\ProfilSortieController;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProfilSortiRepository::class)
 * @ApiResource(collectionOperations={
 *           "get_admin_profilSorti"={ 
 *               "method"="GET", 
 *               "path"="/admin/profilSorti",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé"
 *          },
 * 
 *            "get_admin_profilSorti_apprenants"={ 
 *               "method"="GET", 
 *               "path"="/admin/profilSorti/{id}/apprenants",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé"
 *          },
 *            "create_profilSorti"={ 
 *               "method"="POST", 
 *               "path"="/admin/profilSorti",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé"
 *          }
 *      },
 *      itemOperations={
 *           "get_admin_profilSorti_id"={ 
 *               "method"="GET", 
 *               "path"="/admin/profilSorti/{id}",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé"
 *          },
 * 
 *            "put_admin_profilSorti_id"={ 
 *               "method"="PUT", 
 *               "path"="/admin/profilSorti/{id}",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *            "delete_profil"={ 
 *               "method"="DELETE", 
 *               "path"="/admin/profilSorti/{id}",
 *                "controller"="App\Controller\profilSortiortieController",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                "security_message"="Acces non autorisé",
 *                "route_name"="delete_profilSorti",
 *          },
 *      },
 *
 *       normalizationContext={"groups"={"profilSorti:read","apprenants:read"}},
 *       attributes={"pagination_enabled"=true, "pagination_items_per_page"=2}
 * )
 */
class ProfilSorti
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"apprenants:read", "profilSorti:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"apprenants:read", "profilSorti:read"})
     */
    private $libele;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archivage;

    /**
     * @ORM\OneToMany(targetEntity=Apprenants::class, mappedBy="profilSorti")
     * @ApiSubresource
     * @Groups({"profilSorti:read"})
     */
    private $apprenant;

    public function __construct()
    {
        $this->apprenant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibele(): ?string
    {
        return $this->libele;
    }

    public function setLibele(string $libele): self
    {
        $this->libele = $libele;

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
            $apprenant->setProfilSorti($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenants $apprenant): self
    {
        if ($this->apprenant->contains($apprenant)) {
            $this->apprenant->removeElement($apprenant);
            // set the owning side to null (unless already changed)
            if ($apprenant->getProfilSorti() === $this) {
                $apprenant->setProfilSorti(null);
            }
        }

        return $this;
    }
}
