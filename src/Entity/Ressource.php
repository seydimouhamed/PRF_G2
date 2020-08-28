<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RessourceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"brief:read","ressource:read"}},
 *     denormalizationContext={"groups"={"brief:write","ressource:write"}}
 * )
 * @ORM\Entity(repositoryClass=RessourceRepository::class)
 */
class Ressource
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"ressource:read","brief:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"ressource:read","brief:read"})
     */
    private $Titre;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Groups({"ressource:read","brief:read"})
     */
    private $url;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"ressource:read","brief:read"})
     */
    private $PieceJointe;

    /**
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="ressources")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brief;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"ressource:read","brief:read"})
     */
    private $type;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
