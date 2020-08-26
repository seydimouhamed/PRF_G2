<?php

namespace App\Entity;

use App\Repository\LivrablePartielsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LivrablePartielsRepository::class)
 */
class LivrablePartiels
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"getBriefByOneGroupe","getBriefByPromo"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"getBriefByOneGroupe","getBriefByPromo"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     * @Groups({"getBriefByOneGroupe","getBriefByPromo"})
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     * @Groups({"getBriefByOneGroupe","getBriefByPromo"})
     */
    private $delai;

    /**
     * @ORM\Column(type="date")
     * @Groups({"getBriefByOneGroupe","getBriefByPromo"})
     */
    private $dateCreation;

    /**
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="LivrablePartiels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brief;

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

    public function getBrief(): ?Brief
    {
        return $this->brief;
    }

    public function setBrief(?Brief $brief): self
    {
        $this->brief = $brief;

        return $this;
    }
}
