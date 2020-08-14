<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommunityManagerRepository;

/**
 * @ApiResource(
 *       normalizationContext={"groups"={"user:read","cm:read"}},
 *       denormalizationContext={"groups"={"user:write","cm:write"}},
 *       attributes={"pagination_enabled"=true, "pagination_items_per_page"=2}
 * )
 * @ORM\Entity(repositoryClass=CommunityManagerRepository::class)
 */
class CommunityManager extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups('cm:read')
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
