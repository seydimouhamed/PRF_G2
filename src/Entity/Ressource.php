<?php

namespace App\Entity;

use App\Repository\RessourceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * @ORM\Entity(repositoryClass=RessourceRepository::class)
 * @ApiResource
 */
class Ressource
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"getAllBrief"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *@Groups({"getAllBrief"})
     *
     */
    private $Titre;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Groups({"getAllBrief"})
     */
    private $url;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"getAllBrief"})
     */
    private $PieceJointe;

    /**
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="ressources")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brief;

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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPieceJointe()
    {
        return $this->PieceJointe;
    }

    public function setPieceJointe($PieceJointe): self
    {
        $this->PieceJointe = $PieceJointe;

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
