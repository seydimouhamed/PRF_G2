<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FilDiscussionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(routePrefix="/admin",
 *       normalizationContext={"groups"={"fil:read","livrablePartiel:read"}},
 *       denormalizationContext={"groups"={"fil:write"}})
 * @ORM\Entity(repositoryClass=FilDiscussionRepository::class)
 */
class FilDiscussion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"fil:read","livrablePartiel:read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=LivrablePartiels::class, inversedBy="filDiscussions")
     */
    private $livrables;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"fil:read","livrablePartiel:read"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Commentaires::class, mappedBy="filDiscussion")
     * @Groups({"fil:read","livrablePartiel:read"})
     */
    private $commentaires;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
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
     * @return Collection|Commentaires[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setFilDiscussion($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getFilDiscussion() === $this) {
                $commentaire->setFilDiscussion(null);
            }
        }

        return $this;
    }

    public function getLivrables(): ?LivrablePartiels
    {
        return $this->livrables;
    }

    public function setLivrables(?LivrablePartiels $livrables): self
    {
        $this->livrables = $livrables;

        return $this;
    }
}
