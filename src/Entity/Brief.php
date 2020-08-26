<?php

namespace App\Entity;

use App\Repository\BriefRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * @ORM\Entity(repositoryClass=BriefRepository::class)
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
 *
 *
 * )
 * @ApiFilter(SearchFilter::class, properties={"tag.libelle"})
 */
class Brief
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"getAllBrief"})

     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"getAllBrief"})
     *

     */

    /**
     * @ORM\Column(type="string", length=255)
     *@Groups({"getAllBrief"})
     */
    private $langue;


    private $Titre;

    /**
     * @ORM\Column(type="text")
     *@Groups({"getAllBrief"})
     */
    private $contexte;

    /**
     * @ORM\Column(type="date")
     *@Groups({"getAllBrief"})
     *

     */
    private $DatePoste;

    /**
     * @ORM\Column(type="date")
     *@Groups({"getAllBrief"})

     */
    private $DateLimite;

    /**
     * @ORM\Column(type="text", nullable=true)
     *@Groups({"getAllBrief"})
     */
    private $ListeLivrable;

    /**
     * @ORM\Column(type="text")
     *@Groups({"getAllBrief"})
     */
    private $DescriptionRapide;

    /**
     * @ORM\Column(type="text")
     *@Groups({"getAllBrief"})
     */
    private $ModalitePedagogique;

    /**
     * @ORM\Column(type="text")
     *@Groups({"getAllBrief"})
     */
    private $CricterePerformance;

    /**
     * @ORM\Column(type="text")
     *@Groups({"getAllBrief"})
     */
    private $ModaliteDevaluation;

    /**
     * @ORM\Column(type="blob")
     *@Groups({"getAllBrief"})
     */
    private $ImageExemplaire;


    /**
     * @ORM\OneToMany(targetEntity=Ressource::class, mappedBy="brief")
     *@Groups({"getAllBrief"})
     */
    private $ressources;

    /**
     * @ORM\OneToMany(targetEntity=Niveau::class, mappedBy="brief")
     * @Groups({"getAllBrief"})
     *
     */
    private $niveau;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="briefs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"getBriefByOneGroupe","getBriefByPromo","getOnBriefOnePromo"})
     */
    private $referentiel;

    /**
     * @ORM\ManyToMany(targetEntity=Promotion::class, inversedBy="briefs", cascade = { "persist" })
     * @Groups({"getBriefByOneGroupe"})
     *
     */
    private $promo;

    /**
     * @ORM\ManyToMany(targetEntity=Groupes::class, inversedBy="briefs", cascade = { "persist" })
     */
    private $groupe;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="briefs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"getBriefByOneGroupe","getBriefByPromo","getOnBriefOnePromo"})
     */
    private $formateur;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="briefs")
     *@Groups({"getAllBrief","getBriefByOneGroupe"})
     */
    private $tag;

    /**
     * @ORM\ManyToMany(targetEntity=LivrableAttendus::class, inversedBy="briefs", cascade = { "persist" })
     *@Groups({"getAllBrief","getBriefByOneGroupe"})
     */
    private $LivrableAttendus;

    /**
     * @ORM\OneToMany(targetEntity=LivrablePartiels::class, mappedBy="brief")
     * @Groups({"getBriefByOneGroupe","getBriefByPromo"})
     */
    private $LivrablePartiels;

    /**
     * @ORM\Column(type="string", length=80)
     * @Groups({"getAllBrief","getBriefByOneGroupe"})
     */
    private $StatutBrief;

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
      //  return $this->ImageExemplaire;
          $data = stream_get_contents($this->ImageExemplaire);
          if(!$this->ImageExemplaire){

              fclose($this->ImageExemplaire);
          }


        return base64_encode($data);

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

    /**
     * @return Collection|LivrablePartiels[]
     */
    public function getLivrablePartiels(): Collection
    {
        return $this->LivrablePartiels;
    }

    public function addLivrablePartiel(LivrablePartiels $livrablePartiel): self
    {
        if (!$this->LivrablePartiels->contains($livrablePartiel)) {
            $this->LivrablePartiels[] = $livrablePartiel;
            $livrablePartiel->setBrief($this);
        }

        return $this;
    }

    public function removeLivrablePartiel(LivrablePartiels $livrablePartiel): self
    {
        if ($this->LivrablePartiels->contains($livrablePartiel)) {
            $this->LivrablePartiels->removeElement($livrablePartiel);
            // set the owning side to null (unless already changed)
            if ($livrablePartiel->getBrief() === $this) {
                $livrablePartiel->setBrief(null);
            }
        }

        return $this;
    }

    public function getStatutBrief(): ?string
    {
        return $this->StatutBrief;
    }

    public function setStatutBrief(string $StatutBrief): self
    {
        $this->StatutBrief = $StatutBrief;

        return $this;
    }
}
