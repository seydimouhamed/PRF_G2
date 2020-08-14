<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeCompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      collectionOperations={
 *           "get_grpCompetence"={ 
 *               "method"="GET", 
 *               "path"="/admin/grpecompetences",
 *               "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_CM'))",
 *               "security_message"="Acces non autorisé",
 *          },
 *           "get_grpCompetence_competence"={ 
 *               "method"="GET", 
 *               "path"="/admin/grpecompetences/competences",
 *               "security"="is_granted('ROLE_ADMIN')",
 *               "security_message"="Acces non autorisé",
 *          },
 *            "add_formateur"={ 
 *               "method"="POST", 
 *               "path"="/admin/grpecompetences",
 *               "security"="is_granted('ROLE_ADMIN')",
 *               "security_message"="Acces non autorisé",
 *          }
 *      },
 *      itemOperations={
 *           "get_grpecompetence_id"={ 
 *               "method"="GET", 
 *               "path"="/admin/grpecompetences/{id}",
 *                "defaults"={"id"=null},
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_CM'))",
 *                  "security_message"="Acces non autorisé",
 *          },
 *
 *            "get_grpcompetence_id_competence"={ 
 *               "method"="GET", 
 *               "path"="/admin/grpecompetences/{id}/competences",
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_CM'))",
 *                  "security_message"="Acces non autorisé",
 *          },
 *
 *            "update_grpcompetence_id_competence"={ 
 *               "method"="PUT", 
 *               "path"="/admin/grpecompetences/{id}",
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_CM'))",
 *                  "security_message"="Acces non autorisé",
 *          },
 *      },
 *       normalizationContext={"groups"={"grpcompetence:read"}},
 *       denormalizationContext={"groups"={"grpcompetence:write"}},
 *       attributes={"pagination_enabled"=true, "pagination_items_per_page"=2}
 * )
 * @ORM\Entity(repositoryClass=GroupeCompetenceRepository::class)
 */
class GroupeCompetence
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"grpcompetence:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grpcompetence:read", "grpcompetence:write"})
     */
    private $lidelle;

    /**
     * @ORM\Column(type="text")
     * @Groups({"grpcompetence:read", "grpcompetence:write"})
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, mappedBy="grpCompetences")
     */
    private $referentiels;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="groupeCompetences")
     */
    private $competences;

    public function __construct()
    {
        $this->referentiels = new ArrayCollection();
        $this->competences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLidelle(): ?string
    {
        return $this->lidelle;
    }

    public function setLidelle(string $lidelle): self
    {
        $this->lidelle = $lidelle;

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

    /**
     * @return Collection|Referentiel[]
     */
    public function getReferentiels(): Collection
    {
        return $this->referentiels;
    }

    public function addReferentiel(Referentiel $referentiel): self
    {
        if (!$this->referentiels->contains($referentiel)) {
            $this->referentiels[] = $referentiel;
            $referentiel->addGrpCompetence($this);
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        if ($this->referentiels->contains($referentiel)) {
            $this->referentiels->removeElement($referentiel);
            $referentiel->removeGrpCompetence($this);
        }

        return $this;
    }

    /**
     * @return Collection|Competence[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        if ($this->competences->contains($competence)) {
            $this->competences->removeElement($competence);
        }

        return $this;
    }
}
