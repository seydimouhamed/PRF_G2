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
 *              "getcommentaire"={
 *              "method"="GET",
 *               "path"="/users/promo/{id}/apprenant/{id2}/chats",
 *               "route_name"="getcomment"
 *              },
 *          "envoieComment"={
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
     * Groups({"chat:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * Groups({"chat:read", "chat:write"})
     */
    private $message;


    /**
     * @ORM\Column(type="blob", nullable=true)
     * Groups({"chat:read", "chat:write"})
     */
    private $piecesJointe;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="chats")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Promotion::class, inversedBy="chats")
     */
    private $promo;

    /**
     * @ORM\Column(type="date")
     * Groups({"chat:read", "chat:write"})
     */
    private $date;

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

    public function getPiecesJointe()
    {
        return $this->piecesJointe;
    }

    public function setPiecesJointe($piecesJointe): self
    {
        $this->piecesJointe = $piecesJointe;

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

    public function getPromo(): ?Promotion
    {
        return $this->promo;
    }

    public function setPromo(?Promotion $promo): self
    {
        $this->promo = $promo;

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
}
