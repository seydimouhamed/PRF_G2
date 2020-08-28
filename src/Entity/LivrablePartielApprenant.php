<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LivrablePartielApprenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(routePrefix="/admin",
 *       normalizationContext={"groups"={"livrablePartiel:read","livrable:read"}},
 *       denormalizationContext={"groups"={"livrable:write"}})
 * @ORM\Entity(repositoryClass=LivrablePartielApprenantRepository::class)
 */
class LivrablePartielApprenant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"livrablePartiel:read","livrable:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"livrablePartiel:read","livrable:read"})
     */
    private $delais;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"livrablePartiel:read","livrable:read"})
     */
    private $etat;

    /**
     * @ORM\OneToOne(targetEntity=FilDiscussion::class, cascade={"persist", "remove"})
     * @Groups({"livrablePartiel:read","livrable:read"})
     */
    private $Fil;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="livrablePartielApprenants")
     */
    private $apprenant;

    /**
     * @ORM\ManyToOne(targetEntity=LivrablePartiels::class, inversedBy="livrablePartielApprenants")
     */
    private $livrablePartiel;

    public function __construct()
    {
        $this->apprenant = new ArrayCollection();
        $this->livrablePartiel = new ArrayCollection();
        $this->apprenants = new ArrayCollection();
        $this->livrablePartiels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDelais(): ?\DateTimeInterface
    {
        return $this->delais;
    }

    public function setDelais(\DateTimeInterface $delais): self
    {
        $this->delais = $delais;

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

    public function getFil(): ?FilDiscussion
    {
        return $this->Fil;
    }

    public function setFil(?FilDiscussion $Fil): self
    {
        $this->Fil = $Fil;

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
}
