<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AprenantLivrablePartielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(routePrefix="/admin",
 *       normalizationContext={"groups"={"livrablePartiel:read","livrable:read"}},
 *       denormalizationContext={"groups"={"livrable:write"}})
 * @ORM\Entity(repositoryClass=AprenantLivrablePartielRepository::class)
 */
class AprenantLivrablePartiel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"livrablePartiel:read","livrable:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"livrablePartiel:read","livrable:read"})
     */
    private $etat;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"livrablePartiel:read","livrable:read"})
     */
    private $delai;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="aprenantLivrablePartiels")
     * @Groups({"livrablePartiel:read","livrable:read"})
     */
    private $apprenant;

    /**
     * @ORM\ManyToOne(targetEntity=LivrablePartiels::class, inversedBy="aprenantLivrablePartiels")
     * @Groups({"livrablePartiel:read","livrable:read"})
     */
    private $livrablePartiel;

    /**
     * @ORM\OneToOne(targetEntity=FilDiscution::class, mappedBy="appLivPartiel", cascade={"persist", "remove"})
     * @Groups({"livrablePartiel:read","livrable:read"})
     */
    private $filDiscution;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getDelai(): ?\DateTimeInterface
    {
        return $this->delai;
    }

    public function setDelai(?\DateTimeInterface $delai): self
    {
        $this->delai = $delai;

        return $this;
    }

    public function getApprenant(): ?Apprenant
    {
        return $this->apprenant;
    }

    public function setApprenant(?Apprenant $apprenant): self
    {
        $this->apprenant = $apprenant;

        return $this;
    }

    public function getLivrablePartiel(): ?LivrablePartiels
    {
        return $this->livrablePartiel;
    }

    public function setLivrablePartiel(?LivrablePartiels $livrablePartiel): self
    {
        $this->livrablePartiel = $livrablePartiel;

        return $this;
    }

    public function getFilDiscution(): ?FilDiscution
    {
        return $this->filDiscution;
    }

    public function setFilDiscution(?FilDiscution $filDiscution): self
    {
        $this->filDiscution = $filDiscution;

        // set (or unset) the owning side of the relation if necessary
        $newAppLivPartiel = null === $filDiscution ? null : $this;
        if ($filDiscution->getAppLivPartiel() !== $newAppLivPartiel) {
            $filDiscution->setAppLivPartiel($newAppLivPartiel);
        }

        return $this;
    }

}

