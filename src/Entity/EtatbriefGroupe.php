<?php

namespace App\Entity;

use App\Repository\EtatbriefGroupeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtatbriefGroupeRepository::class)
 */
class EtatbriefGroupe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="etatbriefGroupes")
     */
    private $briefs;

    /**
     * @ORM\ManyToOne(targetEntity=Groupes::class, inversedBy="etatbriefGroupes")
     */
    private $groupe;

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

    public function getBriefs(): ?Brief
    {
        return $this->briefs;
    }

    public function setBriefs(?Brief $briefs): self
    {
        $this->briefs = $briefs;

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
}
