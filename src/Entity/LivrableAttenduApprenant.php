<?php

namespace App\Entity;

use App\Repository\LivrableAttenduApprenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LivrableAttenduApprenantRepository::class)
 */
class LivrableAttenduApprenant
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
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="livrableAttenduApprenants")
     */
    private $apprenant;

    /**
     * @ORM\ManyToOne(targetEntity=LivrableAttendus::class, inversedBy="livrableAttenduApprenants")
     */
    private $livrableAttendu;

    public function __construct()
    {
        $this->apprenant = new ArrayCollection();
        $this->livrableAttendu = new ArrayCollection();
        $this->apprenants = new ArrayCollection();
        $this->livrableAttenduses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getApprenant(): ?Apprenant
    {
        return $this->apprenant;
    }

    public function setApprenant(?Apprenant $apprenant): self
    {
        $this->apprenant = $apprenant;

        return $this;
    }

    public function getLivrableAttendu(): ?LivrableAttendus
    {
        return $this->livrableAttendu;
    }

    public function setLivrableAttendu(?LivrableAttendus $livrableAttendu): self
    {
        $this->livrableAttendu = $livrableAttendu;

        return $this;
    }
}
