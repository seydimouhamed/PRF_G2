<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateurRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      collectionOperations={
 *           "get_formateurs"={ 
 *               "method"="GET", 
 *               "path"="/formateurs",
 *               "security"="( is_granted('ROLE_ADMIN') or is_granted('ROLE_CM'))",
 *               "security_message"="Acces non autorisé",
 *          },
 *            "add_formateur"={ 
 *               "method"="POST", 
 *               "path"="/formateurs",
 *               "security"="is_granted('ROLE_ADMIN')",
 *               "security_message"="Acces non autorisé",
 *          }
 *      },
 *      itemOperations={
 *           "get_formateur_id"={ 
 *               "method"="GET", 
 *               "path"="/formateurs/{id}",
 *                "defaults"={"id"=null},
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_CM'))",
 *                  "security_message"="Acces non autorisé",
 *          },
 *
 *            "modifier_formateur_id"={ 
 *               "method"="PUT", 
 *               "path"="/formateurs/{id}",
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *                  "security_message"="Acces non autorisé",
 *          },
 *      },
 *       normalizationContext={"groups"={"user:read","formateur:read"}},
 *       denormalizationContext={"groups"={"user:write","formateur:write"}},
 *       attributes={"pagination_enabled"=true, "pagination_items_per_page"=2}
 * )
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 */
class Formateur extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Groupes::class, inversedBy="formateurs")
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

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Collection|Groupes[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupes $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
        }

        return $this;
    }

    public function removeGroupe(Groupes $groupe): self
    {
        if ($this->groupes->contains($groupe)) {
            $this->groupes->removeElement($groupe);
        }

        return $this;
    }
}
