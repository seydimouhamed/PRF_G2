<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompetenceRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      collectionOperations={
 *           "get_Competences"={ 
 *               "method"="GET", 
 *               "path"="/admin/competences",
 *               "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_CM'))",
 *               "security_message"="Acces non autorisé",
 *          },
 *            "add_competence"={ 
 *               "method"="POST", 
 *               "path"="/admin/competences",
 *               "security"="is_granted('ROLE_ADMIN')",
 *               "security_message"="Acces non autorisé",
 *          }
 *      },
 *      itemOperations={
 *           "get_competence_id"={ 
 *               "method"="GET", 
 *               "path"="/admin/competences/{id}",
 *                "defaults"={"id"=null},
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_CM'))",
 *                  "security_message"="Acces non autorisé",
 *          },
 *
 *            "update_competence_id"={ 
 *               "method"="PUT", 
 *               "path"="/admin/competences/{id}",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *      },
 *       normalizationContext={"groups"={"competence:read"}},
 *       denormalizationContext={"groups"={"competence:write"}},
 *       attributes={"pagination_enabled"=true, "pagination_items_per_page"=5}
 * )
 * @ORM\Entity(repositoryClass=CompetenceRepository::class)
 */
class Competence
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"competence:read"})
     * @Groups({"niveau:read"})
     * @Groups({"getAllBrief"})
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"competence:read", "competence:write"})
     * @Groups({"niveau:read"})
     * @Groups({"getAllBrief"})
     *
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=250)
     *  @Groups({"competence:read"})
     * @Groups({"niveau:read"})
     * @Groups({"getAllBrief"})
     *
     */
    private $descriptif;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, mappedBy="competences")
     *
     */
    private $groupeCompetences;


    /**
     * @ORM\OneToMany(targetEntity=Niveau::class, mappedBy="competence")
     */
    private $niveaux;

    public function __construct()
    {
        $this->groupeCompetences = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
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
     * @return Collection|GroupeCompetence[]
     */
    public function getGroupeCompetences(): Collection
    {
        return $this->groupeCompetences;
    }

    public function addGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if (!$this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences[] = $groupeCompetence;
            $groupeCompetence->addCompetence($this);
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if ($this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences->removeElement($groupeCompetence);
            $groupeCompetence->removeCompetence($this);
        }

        return $this;
    }

    // public function getNiveau(): ?Niveau
    // {
    //     return $this->niveau;
    // }

    // public function setNiveau(?Niveau $niveau): self
    // {
    //     $this->niveau = $niveau;

    //     return $this;
    // }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveaux->contains($niveau)) {
            $this->niveaux[] = $niveau;
            $niveau->setCompetence($this);
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveaux->contains($niveau)) {
            $this->niveaux->removeElement($niveau);
            // set the owning side to null (unless already changed)
            if ($niveau->getCompetence() === $this) {
                $niveau->setCompetence(null);
            }
        }

        return $this;
    }
}
