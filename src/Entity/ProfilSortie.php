<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilSortieRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;



/**
 * @ApiResource( 
 * 
 *          collectionOperations={
 *              "Lister_promo_ProfilSortie"={
 *              "method"="GET",
 *               "path"="/users/promo/id/apprenant/id/chats",
 *              "route_name"="list_apprenant_profilSortie_promo"
 *              }
 *          },
 *         
 * 
 *       normalizationContext={"groups"={"profilSortie:read", "promo:read"}},
 *       denormalizationContext={"groups"={"profilSortie:write"}},
 *       attributes={"pagination_enabled"=true, "pagination_items_per_page"=2}
 * )
 * @ORM\Entity(repositoryClass=ProfilSortieRepository::class)
 */
class ProfilSortie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"apprenant:read", "profilSortie:read", "profilSortie:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"apprenant:read", "profilSortie:read", "profilSortie:write"})
     * @Groups({"groupe:read"})
     * @Groups({"promo:read"})
     * @Assert\NotBlank
     */
    private $libele;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="profilSortie")
     * @Groups({"profilSortie:read"})
     */
    private $apprenants;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     * @Groups({"profilSortie:write"})
     */
    private $archivage;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
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
    public function getArchivage(): ?bool
    {
        return $this->archivage;
    }

    public function setArchivage(bool $archivage): self
    {
        $this->archivage = $archivage;

        return $this;
    }
    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->setProfilSortie($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->contains($apprenant)) {
            $this->apprenants->removeElement($apprenant);
            // set the owning side to null (unless already changed)
            if ($apprenant->getProfilSortie() === $this) {
                $apprenant->setProfilSortie(null);
            }
        }

        return $this;
    }


}
