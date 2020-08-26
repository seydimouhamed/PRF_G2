<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\NiveauRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *       normalizationContext={"groups"={"niveau:read","brief:read","livrablePartiel:read"}},
 * )
 * @ORM\Entity(repositoryClass=NiveauRepository::class)
 */
class Niveau
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"niveau:read","competence:read","brief:read","livrablePartiel:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"niveau:read","competence:read","brief:read","livrablePartiel:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"niveau:read", "competence:read","brief:read","livrablePartiel:read"})
     */
    private $critereEvaluation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"niveau:read", "competence:read","brief:read","livrablePartiel:read"})
     */
    private $groupeAction;

    /**
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="niveaux")
     */
    private $competence;

    /**
     * @ORM\ManyToMany(targetEntity=LivrablePartiels::class, inversedBy="niveaux")
     */
    private $livrablesPartiels;

    /**
     * @ORM\ManyToMany(targetEntity=Brief::class, inversedBy="niveaux")
     */
    private $brief;

    // /**
    //  * @ORM\OneToMany(targetEntity=Competence::class, mappedBy="niveau")
    //  */
    // private $competences;

    public function __construct()
    {
       // $this->competences = new ArrayCollection();
       $this->livrablesPartiels = new ArrayCollection();
       $this->brief = new ArrayCollection();
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

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

        return $this;
    }

    public function getGroupeAction(): ?string
    {
        return $this->groupeAction;
    }

    public function setGroupeAction(string $groupeAction): self
    {
        $this->groupeAction = $groupeAction;

        return $this;
    }

    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }

    public function setCompetence(?Competence $competence): self
    {
        $this->competence = $competence;

        return $this;
    }

    /**
     * @return Collection|LivrablePartiels[]
     */
    public function getLivrablesPartiels(): Collection
    {
        return $this->livrablesPartiels;
    }

    public function addLivrablesPartiel(LivrablePartiels $livrablesPartiel): self
    {
        if (!$this->livrablesPartiels->contains($livrablesPartiel)) {
            $this->livrablesPartiels[] = $livrablesPartiel;
        }

        return $this;
    }

    public function removeLivrablesPartiel(LivrablePartiels $livrablesPartiel): self
    {
        if ($this->livrablesPartiels->contains($livrablesPartiel)) {
            $this->livrablesPartiels->removeElement($livrablesPartiel);
        }

        return $this;
    }

    /**
     * @return Collection|Brief[]
     */
    public function getBrief(): Collection
    {
        return $this->brief;
    }

    public function addBrief(Brief $brief): self
    {
        if (!$this->brief->contains($brief)) {
            $this->brief[] = $brief;
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->brief->contains($brief)) {
            $this->brief->removeElement($brief);
        }

        return $this;
    }

}
