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
     * @Groups({"livrablePartiel:read"})
     */
    private $niveaux;

    /**
     * @ORM\OneToMany(targetEntity=LivrablePartielApprenant::class, mappedBy="livrablePartiel")
     * @Groups({"livrablePartiel:read"})
     */
    private $livrablePartielApprenants;

    public function __construct()
    {
        $this->niveaux = new ArrayCollection();
        $this->apprenants = new ArrayCollection();
        $this->filDiscussions = new ArrayCollection();
        $this->livrablePartielApprenants = new ArrayCollection();
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
     * @return Collection|LivrablePartielApprenant[]
     */
    public function getLivrablePartielApprenants(): Collection
    {
        return $this->livrablePartielApprenants;
    }

    public function addLivrablePartielApprenant(LivrablePartielApprenant $livrablePartielApprenant): self
    {
        if (!$this->livrablePartielApprenants->contains($livrablePartielApprenant)) {
            $this->livrablePartielApprenants[] = $livrablePartielApprenant;
            $livrablePartielApprenant->setLivrablePartiel($this);
        }

        return $this;
    }

    public function removeLivrablePartielApprenant(LivrablePartielApprenant $livrablePartielApprenant): self
    {
        if ($this->livrablePartielApprenants->contains($livrablePartielApprenant)) {
            $this->livrablePartielApprenants->removeElement($livrablePartielApprenant);
            // set the owning side to null (unless already changed)
            if ($livrablePartielApprenant->getLivrablePartiel() === $this) {
                $livrablePartielApprenant->setLivrablePartiel(null);
            }
        }

        return $this;
    }

}
