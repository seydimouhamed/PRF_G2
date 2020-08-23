<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @ApiResource(
 *      routePrefix="/admin",
 *       normalizationContext={"groups"={"tag:read"}},
 *       denormalizationContext={"groups"={"tag:write"}},
 *       attributes={"pagination_enabled"=true, "pagination_items_per_page"=10}
 * )
 */
class Tag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"tag:read","grptag:read","brief:read"})
     *
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tag:read","tag:write", "grptag:read","brief:read"})
     *
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tag:read","tag:write", "grptag:read","brief:read"})
     *
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Brief::class, mappedBy="tag", cascade = { "persist" })
     */
    private $briefs;

    public function __construct()
    {
        $this->briefs = new ArrayCollection();
    }

    // /**
    //  * @ORM\ManyToMany(targetEntity=GroupeTag::class, mappedBy="tags")
    //  */
    // private $groupeTags;

    // public function __construct()
    // {
    //     $this->groupeTags = new ArrayCollection();
    // }

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

    // /**
    //  * @return Collection|GroupeTag[]
    //  */
    // public function getGroupeTags(): Collection
    // {
    //     return $this->groupeTags;
    // }

    // public function addGroupeTag(GroupeTag $groupeTag): self
    // {
    //     if (!$this->groupeTags->contains($groupeTag)) {
    //         $this->groupeTags[] = $groupeTag;
    //         $groupeTag->addTag($this);
    //     }

    //     return $this;
    // }

    // public function removeGroupeTag(GroupeTag $groupeTag): self
    // {
    //     if ($this->groupeTags->contains($groupeTag)) {
    //         $this->groupeTags->removeElement($groupeTag);
    //         $groupeTag->removeTag($this);
    //     }

    //     return $this;
    // }

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
            $brief->addTag($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->contains($brief)) {
            $this->briefs->removeElement($brief);
            $brief->removeTag($this);
        }

        return $this;
    }
}
