<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\LivrablePartielsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"apprenant:read","livrablePartiel:read"}},
 *     denormalizationContext={"groups"={"livrablePartiel:write"}}
 * )
 * @ORM\Entity(repositoryClass=LivrablePartielsRepository::class)
 */
class LivrablePartiels
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"livrablePartiel:read","apprenant:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"livrablePartiel:read","apprenant:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     * @Groups({"livrablePartiel:read","apprenant:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     * @Groups({"livrablePartiel:read","apprenant:read"})
     */
    private $delai;

    /**
     * @ORM\Column(type="date")
     * @Groups({"livrablePartiel:read","apprenant:read"})
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"livrablePartiel:read","apprenant:read"})
     */
    private $statut;

    /**
     * @ORM\ManyToMany(targetEntity=Niveau::class, mappedBy="livrablesPartiels")
     * @Groups({"livrablePartiel:read","apprenant:read"})
     */
    private $niveaux;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, mappedBy="livrablesPartiels")
     */
    private $apprenants;

    /**
     * @ORM\OneToMany(targetEntity=FilDiscussion::class, mappedBy="livrables")
     * @Groups({"livrablePartiel:read"})
     */
    private $filDiscussions;

    public function __construct()
    {
        $this->niveaux = new ArrayCollection();
        $this->apprenants = new ArrayCollection();
        $this->filDiscussions = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDelai(): ?\DateTimeInterface
    {
        return $this->delai;
    }

    public function setDelai(\DateTimeInterface $delai): self
    {
        $this->delai = $delai;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

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
            $niveau->addLivrablesPartiel($this);
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveaux->contains($niveau)) {
            $this->niveaux->removeElement($niveau);
            $niveau->removeLivrablesPartiel($this);
        }

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->addLivrablesPartiel($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->contains($apprenant)) {
            $this->apprenants->removeElement($apprenant);
            $apprenant->removeLivrablesPartiel($this);
        }

        return $this;
    }

    /**
     * @return Collection|FilDiscussion[]
     */
    public function getFilDiscussions(): Collection
    {
        return $this->filDiscussions;
    }

    public function addFilDiscussion(FilDiscussion $filDiscussion): self
    {
        if (!$this->filDiscussions->contains($filDiscussion)) {
            $this->filDiscussions[] = $filDiscussion;
            $filDiscussion->setLivrables($this);
        }

        return $this;
    }

    public function removeFilDiscussion(FilDiscussion $filDiscussion): self
    {
        if ($this->filDiscussions->contains($filDiscussion)) {
            $this->filDiscussions->removeElement($filDiscussion);
            // set the owning side to null (unless already changed)
            if ($filDiscussion->getLivrables() === $this) {
                $filDiscussion->setLivrables(null);
            }
        }

        return $this;
    }
}
