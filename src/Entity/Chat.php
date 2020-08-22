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
 *              "route_name"="envoyerUncommentaire"
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
     * @Groups({"chat:read", "chat:write"})
     */
    private $pieceJointes;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="chat")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Promotion::class, inversedBy="chat")
     */
    private $promotion;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }
}
