<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EtatBriefGroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EtatBriefGroupeRepository::class)
 * @ApiResource
 *
 */
class EtatBriefGroupe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"getBriefByOneGroupeApp"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"getBriefByOneGroupeApp"})
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity=Groupes::class, inversedBy="etatBriefGroupes")
     * @Groups({"getBriefByOneGroupe"})
     * @Groups({"getBriefByOneGroupeApp"})
     */
    private $groupe;

    /**
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="etatBriefGroupes")
     *  @Groups({"getAllBrief"})
     *
     */
    private $brief;


    public function __construct()
    {
        $this->groupe = new ArrayCollection();
        $this->brief = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getGroupe(): ?Groupes
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupes $groupe): self
    {
        $this->groupe = $groupe;

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
