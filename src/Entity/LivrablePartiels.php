<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LivrablePartielsRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**  
 *
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
     *  @Groups({"livrablePartiel:read","apprenant:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups({"livrablePartiel:read","apprenant:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     *  @Groups({"livrablePartiel:read","apprenant:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     *  @Groups({"livrablePartiel:read","apprenant:read"})
     */
    private $delai;

    /**
     * @ORM\Column(type="date")
     *  @Groups({"livrablePartiel:read","apprenant:read"})
     */
    private $dateCreation;


    /**
     * @ORM\Column(type="string", length=50)
     *  @Groups({"livrablePartiel:read","apprenant:read"})
     */
    private $etat;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *  @Groups({"livrablePartiel:read","apprenant:read"})
     */
    private $nbreRendu;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *  @Groups({"livrablePartiel:read","apprenant:read"})
     */
    private $nbreCorriger;

    /**
     * @ORM\ManyToOne(targetEntity=BriefMaPromo::class, inversedBy="livrablePartiels")
     */
    private $briefMaPromo;

    /**
     * @ORM\OneToMany(targetEntity=AprenantLivrablePartiel::class, mappedBy="livrablePartiel")
     * * @Groups({"livrablePartiel:read"})
     */
    private $aprenantLivrablePartiels;

    /**
     * @ORM\ManyToMany(targetEntity=Niveau::class, inversedBy="livrablePartiels")
     * @Groups({"livrablePartiel:read"})
     */
    private $niveau;

    public function __construct()
    {
        $this->aprenantLivrablePartiels = new ArrayCollection();
        $this->niveau = new ArrayCollection();
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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNbreRendu(): ?int
    {
        return $this->nbreRendu;
    }

    public function setNbreRendu(?int $nbreRendu): self
    {
        $this->nbreRendu = $nbreRendu;

        return $this;
    }

    public function getNbreCorriger(): ?int
    {
        return $this->nbreCorriger;
    }

    public function setNbreCorriger(?int $nbreCorriger): self
    {
        $this->nbreCorriger = $nbreCorriger;

        return $this;
    }

    public function getBriefMaPromo(): ?BriefMaPromo
    {
        return $this->briefMaPromo;
    }

    public function setBriefMaPromo(?BriefMaPromo $briefMaPromo): self
    {
        $this->briefMaPromo = $briefMaPromo;

        return $this;
    }

    /**
     * @return Collection|AprenantLivrablePartiel[]
     */
    public function getAprenantLivrablePartiels(): Collection
    {
        return $this->aprenantLivrablePartiels;
    }

    public function addAprenantLivrablePartiel(AprenantLivrablePartiel $aprenantLivrablePartiel): self
    {
        if (!$this->aprenantLivrablePartiels->contains($aprenantLivrablePartiel)) {
            $this->aprenantLivrablePartiels[] = $aprenantLivrablePartiel;
            $aprenantLivrablePartiel->setLivrablePartiel($this);
        }

        return $this;
    }

    public function removeAprenantLivrablePartiel(AprenantLivrablePartiel $aprenantLivrablePartiel): self
    {
        if ($this->aprenantLivrablePartiels->contains($aprenantLivrablePartiel)) {
            $this->aprenantLivrablePartiels->removeElement($aprenantLivrablePartiel);
            // set the owning side to null (unless already changed)
            if ($aprenantLivrablePartiel->getLivrablePartiel() === $this) {
                $aprenantLivrablePartiel->setLivrablePartiel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveau(): Collection
    {
        return $this->niveau;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveau->contains($niveau)) {
            $this->niveau[] = $niveau;
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveau->contains($niveau)) {
            $this->niveau->removeElement($niveau);
        }

        return $this;
    }
}
