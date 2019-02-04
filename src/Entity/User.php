<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Traits\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user_account")
 * @UniqueEntity("email", message="user.email.already.exist")
 * @UniqueEntity("login", message="user.login.already.exist")
 */
class User implements UserInterface
{
    use TimestampableTrait;

    /*
     * User roles
     */
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(
     *     message = "user.email.format"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(
     *     min = 8,
     *     max = 100,
     *     minMessage = "user.password.min.message",
     *     maxMessage = "user.password.max.message"
     * )
     * @Assert\Regex(
     *     pattern="/^(?=.{3,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/",
     *     match=true,
     *     message="user.password.format.message"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "user.login.empty"
     * )
     * @Assert\Regex(
     *     pattern="/^[^<>%\$\/\\\s]*$/",
     *     match=true,
     *     message="user.login.special.char"
     * )
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":null})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":null})
     */
    private $lastname;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Api", mappedBy="creator", orphanRemoval=true)
     */
    private $apis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email_token;

    public function __construct()
    {
        $this->apis = new ArrayCollection();
    }

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
        $roles[] = 'ROLE_USER';

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

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Api[]
     */
    public function getApis(): Collection
    {
        return $this->apis;
    }

    public function addApi(Api $api): self
    {
        if (!$this->apis->contains($api)) {
            $this->apis[] = $api;
            $api->setCreator($this);
        }

        return $this;
    }

    public function removeApi(Api $api): self
    {
        if ($this->apis->contains($api)) {
            $this->apis->removeElement($api);
            // set the owning side to null (unless already changed)
            if ($api->getCreator() === $this) {
                $api->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * Returns the existing roles.
     *
     * @return array
     */
    public static function getExistingRoles(): array
    {
        return [self::ROLE_USER, self::ROLE_ADMIN];
    }

    public function getEmailToken(): ?string
    {
        return $this->email_token;
    }

    public function setEmailToken(?string $email_token): self
    {
        $this->email_token = $email_token;

        return $this;
    }
}
