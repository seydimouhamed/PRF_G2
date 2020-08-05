<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeTagRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GroupeTagRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *           "get_groupeTags"={ 
 *               "method"="GET", 
 *               "path"="/admin/groupeTags",
 *               "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *               "security_message"="Acces non autorisé",
 *          },
 *            "add_groupeTags"={ 
 *               "method"="POST", 
 *               "path"="/admin/groupeTags",
 *               "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *               "security_message"="Acces non autorisé",
 *          }
 *      },
 *      itemOperations={
 *           "get_groupeTag_id"={ 
 *               "method"="GET", 
 *               "path"="/admin/groupeTags/{id}",
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') )",
 *                  "security_message"="Acces non autorisé",
 *          },
 *          "get_groupeTag_id_tags"={ 
 *               "method"="GET", 
 *               "path"="/admin/groupeTags/{id}/tags",
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') )",
 *                  "security_message"="Acces non autorisé",
 *          },
 *            "update_groupeTag_id"={ 
 *               "method"="PUT", 
 *               "path"="/admin/groupeTags/{id}",
 *                "security"="(is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN'))",
 *                  "security_message"="Acces non autorisé",
 *          },
 *      },
 *       normalizationContext={"groups"={"groupeTag:read"}},
 *       denormalizationContext={"groups"={"groupeTag:write"}},
 *       attributes={"pagination_enabled"=true, "pagination_items_per_page"=10}
 * )
 */
class GroupeTag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"groupeTag:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupeTag:read","groupeTag:write"})
     */
    private $libele;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="groupeTags")
     * @ApiSubresource
     * @Groups({"groupeTag:read"})
     */
    private $tag;

    public function __construct()
    {
        $this->tag = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibele(): ?string
    {
        return $this->libele;
    }

    public function setLibele(string $libele): self
    {
        $this->libele = $libele;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tag->contains($tag)) {
            $this->tag->removeElement($tag);
        }

        return $this;
    }
}
