<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommentairesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(routePrefix="/admin",
 *       normalizationContext={"groups"={"commentaire:read","fil:read"}},
 *       denormalizationContext={"groups"={"fil:write"}})
 * @ORM\Entity(repositoryClass=CommentairesRepository::class)
 */
class Commentaires
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"commentaire:read","fil:read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=FilDiscussion::class, inversedBy="commentaires")
     */
    private $filDiscussion;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="commentaires")
     */
    private $formateurs;

    /**
     * @ORM\Column(type="text")
     * @Groups({"commentaire:read","fil:read"})
     */
    private $commentaire;

    public function __construct()
    {
        $this->formateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilDiscussion(): ?FilDiscussion
    {
        return $this->filDiscussion;
    }

    public function setFilDiscussion(?FilDiscussion $filDiscussion): self
    {
        $this->filDiscussion = $filDiscussion;

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        if ($this->formateurs->contains($formateur)) {
            $this->formateurs->removeElement($formateur);
        }

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }
}
