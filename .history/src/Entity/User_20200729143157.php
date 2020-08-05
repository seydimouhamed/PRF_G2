<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\InheritanceType;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("JOINED")
* @ORM\DiscriminatorColumn(name="discr", type="string")
* @ORM\DiscriminatorMap({"user"="User","apprenant" = "Apprenant","formateur"="Formateur"})
 * @ApiResource(
 *      collectionOperations={
 *           "get_admin_users"={ 
 *               "method"="GET", 
 *               "path"="/admin/users",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *            "add_users"={ 
 *               "method"="POST", 
 *               "path"="/admin/users",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                 "security_message"="Acces non autorisé",
 *          },
 *      },
 *      itemOperations={
 *           "get_admin_users_id"={ 
 *               "method"="GET", 
 *               "path"="/admin/users/{id}",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 * 
 *            "modifier_admin_users_id"={ 
 *               "method"="PUT", 
 *               "path"="/admin/users/{id}",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *         "delete_users"={ 
 *               "method"="DELETE", 
 *               "path"="/admin/profils/{id}/users",
 *                "controller"="App\Controller\ProfilArchiveController",
 *                "security"="is_granted('ROLE_ADMINISTRATEUR')",
 *                  "security_message"="Acces non autorisé",
 *                  "swagger_context"={
 *                                          "summary"="archive un profil",
 *                                          "description"="Ne supprime pas un profil mais il change le status qui nous sert de corbeille",
 *                                      }
 *                  
 *          },
 * 
 *      },
 *       normalizationContext={"groups"={"user:read"}},
 *       denormalizationContext={"groups"={"user:write"}},
 * attributes={"pagination_enabled"=true, "pagination_items_per_page"=2}
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * 
     * @Groups({"user:read", "user:write","profil:read"})
     */
    private $username;


    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Groups("user:write")
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Groups({"user:read", "user:write", "profil:read"})
     */
    private $fisrtName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     *  @Groups({"user:read", "user:write", "profil:read"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     *  @Groups({"user:read", "user:write", "profil:read"})
     */
    private $email;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * 
     *  @Groups({"user:read", "user:write"})
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     */
    private $profil;

    /**
     * @ORM\Column(type="boolean",options={"default" : false})
     */
    private $archivage;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getAbbr();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {

        // If you store any temporary, sensitive data on the user, clear it here
         $this->plainPassword = null;
    }

    public function getFisrtName(): ?string
    {
        return $this->fisrtName;
    }

    public function setFisrtName(string $fisrtName): self
    {
        $this->fisrtName = $fisrtName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }


    public function getPhoto()
    {
         $data = stream_get_contents($this->photo);
         fclose($this->photo);

        return base64_encode($data);
    }

    public function setPhoto($photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get the value of plainPassword
     */ 
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set the value of plainPassword
     *
     * @return  self
     */ 
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

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
}
