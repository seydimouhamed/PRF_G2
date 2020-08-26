<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommunityManagerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *          routePrefix="/admin",
 *      itemOperations={
 *           "get_cm_id"={ 
 *               "method"="GET", 
 *               "path"="/cm/{id}",
 *                "defaults"={"id"=null},
 *              }
 *          },
 *       normalizationContext={"groups"={"user:read","cm:read"}},
 *       denormalizationContext={"groups"={"user:write","cm:write"}},
 * )
 * @ORM\Entity(repositoryClass=CommunityManagerRepository::class)
 */
class CommunityManager extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"cm:read"})
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
