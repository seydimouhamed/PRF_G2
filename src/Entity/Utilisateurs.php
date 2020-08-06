<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InheritanceType;
use App\Repository\UtilisateursRepository;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UtilisateursRepository::class)
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"user"="Utilisateurs","apprenant" = "Apprenants","formateur"="Formateurs"})
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
 *            "modifier_admin_users_id"={ 
 *               "method"="PUT", 
 *               "path"="/admin/users/{id}",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Acces non autorisé",
 *          },
 *            "supprimer_admin_users_id"={ 
 *               "method"="DELETE", 
 *               "path"="/admin/users/{id}",
 *                "controller"="App\Controller\UtilisateursController",
 *                "security"="is_granted('ROLE_ADMIN')",
 *                "security_message"="Acces non autorisé",
 *                "route_name"="archive_user",
 *          },
 *      },
 *       normalizationContext={"groups"={"user:read"}},
 *       denormalizationContext={"groups"={"user:write"}},
 * attributes={"pagination_enabled"=true, "pagination_items_per_page"=2}
 * )
 */
class Utilisateurs implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user:read","profil:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user:read", "user:write","profil:read"})
     */
    private $email;

    /**
     * ORM\Column(type="json")
     *  
     * 
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     *
     */
    private $password;

     /**
     *@Groups({"user:write"})
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255)
     *@Groups({"user:read", "user:write","profil:read"})
     *
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     *@Groups({"user:read", "user:write","profil:read"})
     */
    private $nom;

    /**
     * @ORM\Column(type="blob")
     *@Groups({"user:read", "user:write","profil:read"})
     * 
     */
    private $avatar;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="utilisateur")
     * @ApiSubresource
     * @Groups({"user:read"})
     * 
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getLibelle();

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
        // $this->plainPassword = null;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAvatar()
    {
        $data = stream_get_contents($this->avatar);
        fclose($this->avatar);

       return base64_encode($data);
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

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
