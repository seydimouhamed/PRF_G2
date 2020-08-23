<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ChatRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;



/**
 * @ApiResource(
 * 
 *          collectionOperations={
 *              "envoieComment"={
 *              "method"="POST",
 *               "path"="/users/promo/{id}/apprenant/{id2}/chats",
 *               "route_name"="envoyerUncommentaire"
 *              }
 *          },
 *         
 *       normalizationContext={"groups"={"chat:read", "promo:read"}},
 *       denormalizationContext={"groups"={"chat:write"}},
 *       attributes={"pagination_enabled"=true, "pagination_items_per_page"=2}

 * )
 * @ORM\Entity(repositoryClass=ChatRepository::class)
 */

class Chat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"chat:read", "chat:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"chat:read", "chat:write"})
     */
    private $message;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * Groups({"chat:read", "chat:write"})
     */
    private $pieceJointes;

    /**
     * @ORM\Column(type="date")
     * @Groups({"chat:read", "chat:write"})
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="chats")
     */
    private $apprenant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getPieceJointes()
    {
        return $this->pieceJointes;
    }

    public function setPieceJointes($pieceJointes): self
    {
        $this->pieceJointes = $pieceJointes;

        return $this;
    }


    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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
}
