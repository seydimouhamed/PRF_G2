<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LivrableRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *            "addLivrable"={
 *               "method"="POST",
 *               "path"="/apprenants/{id}/groupe/{id1}/livrables",
 *                 "security_message"="Acces non autorisé",
 *          },
 *     },
 *       normalizationContext={"groups"={"livrableAttendus:read","livrable:read","brief:read"}},
 *     denormalizationContext={"groups"={"livrable:write"}}
 * )
 * @ORM\Entity(repositoryClass=LivrableRepository::class)
 */
class Livrable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"livrableAttendus:read","livrable:read","brief:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"livrableAttendus:read","livrable:read","brief:read"})
     * @Groups({"livrable:write"})
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="livrableAttendus")
     * @Groups({"livrable:read"})
     * @Groups({"livrable:write"})
     */
    private $apprenant;

    /**
     * @ORM\ManyToOne(targetEntity=LivrableAttendus::class, inversedBy="livrables")
     * @Groups({"livrable:write"})
     */
    private $livrableAttendu;

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
