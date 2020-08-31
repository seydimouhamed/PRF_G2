<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BriefRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      routePrefix="/formateurs",
 *     collectionOperations={
 *                      "get_bief"={
 *                                         "method"="GET",
 *                                        "path"="/briefs",
 *                                         "route_name"="get_brief_all",
 *                                         "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_CM'))",
 *
 *                                   },
 *                      "get_brief_one_groupe"={
 *                                                  "method"="GET",
 *                                                  "path"="/promo/{id}/groupe/{id1}/briefs",
 *                                                  "route_name"="get_brief_by_one_groupe",
 *                                                   "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_CM'))",
 *
 *
 *                                          },
 *                      "get_brief_one_promo"={
 *                                               "methode"="GET",
 *                                               "path"="/promos/{id}/briefs",
 *                                               "route_name"="get_brief_by_one_promo",
 *                                                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_CM'))",
 *                                           },
 *                      "get_brief_one_formateur"={
 *                                                   "method"="GET",
 *                                                  "path"="/{id}/briefs/broullons",
 *                                                  "route_name"="get_brief_by_one_formateur",
 *                                                  "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *
 *                                              },
 *                      "get_one_brief_one_promo"={
 *                                                       "method"="GET",
 *                                                      "path"="/promo/{id}/briefs/{id1}",
 *                                                      "route_name"="get_on_brief_on_promo",
 *                                                       "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *
 *
 *                                                  },
 *                      "get_brief_valide_assigner_one_formateur"={
 *                                                                  "method"="GET",
 *                                                                  "path"="/{id}/briefs/valide",
 *                                                                  "route_name"="get_brief_valide_assigner_on_formateur",
 *                                                                   "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *
 *                                                              },
 *     },
 *              normalizationContext={"groups"={"brief:read","BriefMaPromo:read"}},
 *           denormalizationContext={"groups"={"brief:write"}}
 * )
 *
 * )
 * @ORM\Entity(repositoryClass=BriefRepository::class)
 */
class Brief
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"getAllBrief"})
     * @Groups({"brief:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *@Groups({"getAllBrief"})
     * @Groups({"brief:read","brief:write"})
     *
     */
    private $Titre;

    /**
     * @ORM\Column(type="text")
     *@Groups({"getAllBrief"})
     * @Groups({"brief:read","brief:write"})
     */
    private $contexte;

    /**
     * @ORM\Column(type="date")
     *@Groups({"getAllBrief"})
     * @Groups({"brief:read","brief:write"})
     */
    private $DatePoste;

    /**
     * @ORM\Column(type="date")
     *@Groups({"getAllBrief"})
     * @Groups({"brief:read","brief:write"})
     */
    private $DateLimite;

    /**
     * @ORM\Column(type="text", nullable=true)
     *@Groups({"getAllBrief"})
     * @Groups({"brief:read","brief:write"})
     */
    private $ListeLivrable;

    /**
     * @ORM\Column(type="text")
     *@Groups({"getAllBrief"})
     * @Groups({"brief:read","brief:write"})
     */
    private $DescriptionRapide;

    /**
     * @ORM\Column(type="text")
     *@Groups({"getAllBrief"})
     * @Groups({"brief:read","brief:write"})
     */
    private $ModalitePedagogique;

    /**
     * @ORM\Column(type="text")
     *@Groups({"getAllBrief"})
     * @Groups({"brief:read","brief:write"})
     */
    private $CricterePerformance;

    /**
     * @ORM\Column(type="text")
     *@Groups({"getAllBrief"})
     * @Groups({"brief:read","brief:write"})
     */
    private $ModaliteDevaluation;

    /**
     * @ORM\Column(type="blob",nullable=true)
     *@Groups({"getAllBrief"})
     *@Groups({"brief:read","brief:write"})
     */
    private $ImageExemplaire;

    /**
     * @ORM\Column(type="string", length=255)
     *@Groups({"getAllBrief"})
     * @Groups({"brief:read","brief:write"})
     */
    private $langue;

    /**
     * @ORM\OneToMany(targetEntity=Ressource::class, mappedBy="brief")
     *@Groups({"getAllBrief"})
     * @Groups({"brief:read","brief:write"})
     */
    private $ressources;

    /**
     * @ORM\ManyToMany(targetEntity=Niveau::class, inversedBy="brief")
     * @Groups({"getAllBrief"})
     * @Groups({"brief:read","brief:write"})
     */
    private $niveau;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="briefs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"getBriefByOneGroupe","getOnBriefOnePromo"})
     * @Groups({"brief:read","brief:write"})
     *
     */
    private $referentiel;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="briefs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"getOnBriefOnePromo"})
     * @Groups({"brief:read","brief:write"})
     */
    private $formateur;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="briefs")
     * @Groups({"getAllBrief"})
     * @Groups({"brief:read"})
     */
    private $tag;

    /**
     * @ORM\ManyToMany(targetEntity=LivrableAttendus::class, inversedBy="briefs")
     * @Groups({"getAllBrief"})
     * @Groups({"brief:read","brief:write"})
     */
    private $LivrableAttendus;


    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"getAllBrief"})
     * @Groups({"brief:read","brief:write"})
     */
    private $statut;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archivage;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity=BriefMaPromo::class, mappedBy="brief")
     * @Groups({"getBriefByOneGroupePr"})
     * @Groups({"brief:read"})
     */
    private $briefMaPromos;

    /**
     * @ORM\OneToMany(targetEntity=EtatBriefGroupe::class, mappedBy="brief")
     * @Groups({"getBriefByOneGroupeApp"})
     */
    private $etatBriefGroupes;


    public function __construct()
    {
        $this->ressources = new ArrayCollection();
        $this->niveau = new ArrayCollection();
        $this->promo = new ArrayCollection();
        $this->groupe = new ArrayCollection();
        $this->tag = new ArrayCollection();

        $this->LivrableAttendus = new ArrayCollection();

        $this->briefMaPromos = new ArrayCollection();
        $this->etatBriefGroupes = new ArrayCollection();
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
        if($this->ImageExemplaire){

            $data = stream_get_contents($this->ImageExemplaire);
            fclose($this->ImageExemplaire);
            return base64_encode($data);
        }
        else
        {
            return null;
        }

       // return $this->ImageExemplaire;
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

    

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

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
     * @return Collection|BriefMaPromo[]
     */
    public function getBriefMaPromos(): Collection
    {
        return $this->briefMaPromos;
    }

    public function addBriefMaPromo(BriefMaPromo $briefMaPromo): self
    {
        if (!$this->briefMaPromos->contains($briefMaPromo)) {
            $this->briefMaPromos[] = $briefMaPromo;
            $briefMaPromo->setBrief($this);
        }

        return $this;
    }

    public function removeBriefMaPromo(BriefMaPromo $briefMaPromo): self
    {
        if ($this->briefMaPromos->contains($briefMaPromo)) {
            $this->briefMaPromos->removeElement($briefMaPromo);
            // set the owning side to null (unless already changed)
            if ($briefMaPromo->getBrief() === $this) {
                $briefMaPromo->setBrief(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EtatBriefGroupe[]
     */
    public function getEtatBriefGroupes(): Collection
    {
        return $this->etatBriefGroupes;
    }

    public function addEtatBriefGroupe(EtatBriefGroupe $etatBriefGroupe): self
    {
        if (!$this->etatBriefGroupes->contains($etatBriefGroupe)) {
            $this->etatBriefGroupes[] = $etatBriefGroupe;
            $etatBriefGroupe->setBrief($this);
        }

        return $this;
    }

    public function removeEtatBriefGroupe(EtatBriefGroupe $etatBriefGroupe): self
    {
        if ($this->etatBriefGroupes->contains($etatBriefGroupe)) {
            $this->etatBriefGroupes->removeElement($etatBriefGroupe);
            // set the owning side to null (unless already changed)
            if ($etatBriefGroupe->getBrief() === $this) {
                $etatBriefGroupe->setBrief(null);
            }
        }

        return $this;
    }


}
