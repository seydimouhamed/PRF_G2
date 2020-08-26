<?php

namespace App\Entity;

use App\Repository\LivrableAttendusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LivrableAttendusRepository::class)
 * @ApiResource(
 *      attributes={   "normalization_context"={"groups"={"livrableAttendu:read"}},
 *                     "denormalization_context"={"groups"={"livrableAttendu:write"}},}
 * )
 *
 */
class LivrableAttendus
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"livrableAttendu:read"})
     * @Groups({"getAllBrief"})
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"livrableAttendu:read"})
     * @Groups({"getAllBrief"})
     *
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Brief::class, mappedBy="LivrableAttendus", cascade = { "persist" })
     */
    private $briefs;

    public function __construct()
    {
        $this->briefs = new ArrayCollection();
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

    /**
     * @return Collection|Brief[]
     */
    public function getBriefs(): Collection
    {
        return $this->briefs;
    }

    public function addBrief(Brief $brief): self
    {
        if (!$this->briefs->contains($brief)) {
            $this->briefs[] = $brief;
            $brief->addLivrableAttendu($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->contains($brief)) {
            $this->briefs->removeElement($brief);
            $brief->removeLivrableAttendu($this);
        }

        return $this;
    }
}
