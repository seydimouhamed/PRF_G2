<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\BriefRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *            "addbrief"={
 *               "method"="POST",
 *               "path"="/formateurs/briefs",
 *                 "security_message"="Acces non autorisé",
 *          },
 *          "get"={
 *               "method"="GET",
 *               "path"="/formateurs/{id}/promo/{id1}/briefs/{id2}",
 *                 "security_message"="Acces non autorisé",
 *                  "defaults"={"id"=null},
 *          }
 *     },
 *     normalizationContext={"groups"={"brief:read"}},
 *     denormalizationContext={"groups"={"brief:write"}}
 * )
 * @ORM\Entity(repositoryClass=BriefRepository::class)
 */
class Brief
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"brief:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read","brief:write"})
     */
    private $Titre;

    /**
     * @ORM\Column(type="text")
     * @Groups({"brief:read","brief:write"})
     */
    private $contexte;

    /**
     * @ORM\Column(type="date")
     * @Groups({"brief:read","brief:write"})
     */
    private $DatePoste;

    /**
     * @ORM\Column(type="date")
     * @Groups({"brief:read","brief:write"})
     */
    private $DateLimite;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"brief:read","brief:write"})
     */
    private $ListeLivrable;

    /**
     * @ORM\Column(type="text")
     * @Groups({"brief:read","brief:write"})
     */
    private $DescriptionRapide;

    /**
     * @ORM\Column(type="text")
     * @Groups({"brief:read","brief:write"})
     */
    private $ModalitePedagogique;

    /**
     * @ORM\Column(type="text")
     * @Groups({"brief:read","brief:write"})
     */
    private $CricterePerformance;

    /**
     * @ORM\Column(type="text")
     * @Groups({"brief:read","brief:write"})
     */
    private $ModaliteDevaluation;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"brief:read","brief:write"})
     */
    private $ImageExemplaire;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read","brief:write"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read","brief:write"})
     */
    private $etat;


    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $archivage;

    /**
     * @ORM\OneToMany(targetEntity=Ressource::class, mappedBy="brief")
     * @ApiSubresource()
     * @Groups({"brief:read","brief:write"})
     */
    private $ressources;

    /**
     * @ORM\OneToMany(targetEntity=Niveau::class, mappedBy="brief")
     * @ApiSubresource()
     * @Groups({"brief:read","brief:write"})
     */
    private $niveau;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="briefs")
     * @ORM\JoinColumn(nullable=false)
     * @ApiSubresource()
     * @Groups({"brief:read","brief:write"})
     */
    private $referentiel;

    /**
     * @ORM\ManyToMany(targetEntity=Promotion::class, inversedBy="briefs", cascade = { "persist" })
     * @ApiSubresource()
     * @Groups({"brief:write"})
     */
    private $promo;

    /**
     * @ORM\ManyToMany(targetEntity=Groupes::class, inversedBy="briefs", cascade = { "persist" })
     */
    private $groupe;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="briefs")
     * @ORM\JoinColumn(nullable=false)
     * @ApiSubresource()
     * @Groups({"brief:read","brief:write"})
     */
    private $formateur;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="briefs")
     * @ApiSubresource()
     * @Groups({"brief:read","brief:write"})
     */
    private $tag;

    /**
     * @ORM\ManyToMany(targetEntity=LivrableAttendus::class, inversedBy="briefs", cascade = { "persist" })
     * @ApiSubresource()
     * @Groups({"brief:read","brief:write"})
     */
    private $LivrableAttendus;

    public function getUid()
    {
        return (string) $this->uid;
    }

    public function __construct()
    {
        $this->ressources = new ArrayCollection();
        $this->niveau = new ArrayCollection();
        $this->promo = new ArrayCollection();
        $this->groupe = new ArrayCollection();
        $this->tag = new ArrayCollection();
        $this->LivrableAttendus = new ArrayCollection();
        $this->LivrablePartiels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $Titre): self
    {
        $this->Titre = $Titre;

        return $this;
    }

    public function getContexte(): ?string
    {
        return $this->contexte;
    }

    public function setContexte(string $contexte): self
    {
        $this->contexte = $contexte;

        return $this;
    }

    public function getDatePoste(): ?\DateTimeInterface
    {
        return $this->DatePoste;
    }

    public function setDatePoste(\DateTimeInterface $DatePoste): self
    {
        $this->DatePoste = $DatePoste;

        return $this;
    }

    public function getDateLimite(): ?\DateTimeInterface
    {
        return $this->DateLimite;
    }

    public function setDateLimite(\DateTimeInterface $DateLimite): self
    {
        $this->DateLimite = $DateLimite;

        return $this;
    }

    public function getListeLivrable(): ?string
    {
        return $this->ListeLivrable;
    }

    public function setListeLivrable(?string $ListeLivrable): self
    {
        $this->ListeLivrable = $ListeLivrable;

        return $this;
    }

    public function getDescriptionRapide(): ?string
    {
        return $this->DescriptionRapide;
    }

    public function setDescriptionRapide(string $DescriptionRapide): self
    {
        $this->DescriptionRapide = $DescriptionRapide;

        return $this;
    }

    public function getModalitePedagogique(): ?string
    {
        return $this->ModalitePedagogique;
    }

    public function setModalitePedagogique(string $ModalitePedagogique): self
    {
        $this->ModalitePedagogique = $ModalitePedagogique;

        return $this;
    }

    public function getCricterePerformance(): ?string
    {
        return $this->CricterePerformance;
    }

    public function setCricterePerformance(string $CricterePerformance): self
    {
        $this->CricterePerformance = $CricterePerformance;

        return $this;
    }

    public function getModaliteDevaluation(): ?string
    {
        return $this->ModaliteDevaluation;
    }

    public function setModaliteDevaluation(string $ModaliteDevaluation): self
    {
        $this->ModaliteDevaluation = $ModaliteDevaluation;

        return $this;
    }

    public function getImageExemplaire()
    {
        /*$data = stream_get_contents($this->ImageExemplaire);
        fclose($this->ImageExemplaire);

        return base64_encode($data);*/
        return $this->ImageExemplaire;
    }

    public function setImageExemplaire($ImageExemplaire): self
    {
        $this->ImageExemplaire = $ImageExemplaire;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

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

    /**
     * @return Collection|Ressource[]
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressource $ressource): self
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources[] = $ressource;
            $ressource->setBrief($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): self
    {
        if ($this->ressources->contains($ressource)) {
            $this->ressources->removeElement($ressource);
            // set the owning side to null (unless already changed)
            if ($ressource->getBrief() === $this) {
                $ressource->setBrief(null);
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
            $niveau->setBrief($this);
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveau->contains($niveau)) {
            $this->niveau->removeElement($niveau);
            // set the owning side to null (unless already changed)
            if ($niveau->getBrief() === $this) {
                $niveau->setBrief(null);
            }
        }

        return $this;
    }

    public function getReferentiel(): ?Referentiel
    {
        return $this->referentiel;
    }

    public function setReferentiel(?Referentiel $referentiel): self
    {
        $this->referentiel = $referentiel;

        return $this;
    }

    /**
     * @return Collection|Promotion[]
     */
    public function getPromo(): Collection
    {
        return $this->promo;
    }

    public function addPromo(Promotion $promo): self
    {
        if (!$this->promo->contains($promo)) {
            $this->promo[] = $promo;
        }

        return $this;
    }

    public function removePromo(Promotion $promo): self
    {
        if ($this->promo->contains($promo)) {
            $this->promo->removeElement($promo);
        }

        return $this;
    }

    /**
     * @return Collection|Groupes[]
     */
    public function getGroupe(): Collection
    {
        return $this->groupe;
    }

    public function addGroupe(Groupes $groupe): self
    {
        if (!$this->groupe->contains($groupe)) {
            $this->groupe[] = $groupe;
        }

        return $this;
    }

    public function removeGroupe(Groupes $groupe): self
    {
        if ($this->groupe->contains($groupe)) {
            $this->groupe->removeElement($groupe);
        }

        return $this;
    }

    public function getFormateur(): ?Formateur
    {
        return $this->formateur;
    }

    public function setFormateur(?Formateur $formateur): self
    {
        $this->formateur = $formateur;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tag->contains($tag)) {
            $this->tag->removeElement($tag);
        }

        return $this;
    }

    /**
     * @return Collection|LivrableAttendus[]
     */
    public function getLivrableAttendus(): Collection
    {
        return $this->LivrableAttendus;
    }

    public function addLivrableAttendu(LivrableAttendus $livrableAttendu): self
    {
        if (!$this->LivrableAttendus->contains($livrableAttendu)) {
            $this->LivrableAttendus[] = $livrableAttendu;
        }

        return $this;
    }

    public function removeLivrableAttendu(LivrableAttendus $livrableAttendu): self
    {
        if ($this->LivrableAttendus->contains($livrableAttendu)) {
            $this->LivrableAttendus->removeElement($livrableAttendu);
        }

        return $this;
    }

    public function getArchivage(): ?bool
    {
        return $this->archivage;
    }

    public function setArchivage(?bool $archivage): self
    {
        $this->archivage = $archivage;

        return $this;
    }

}
