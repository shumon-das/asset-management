<?php

namespace App\Entity;

use App\Common\CommonMethodsTrait\DeletedMethodsTraits\DeletedMethodsTrait;
use App\Common\CommonMethodsTrait\UserMethodsTraits\UserMethodsTrait;
use App\Common\UuidMethodsTrait;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;


#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee implements UserInterface, PasswordAuthenticatedUserInterface
{
    use DeletedMethodsTrait;
    use UserMethodsTrait;
//    use UuidMethodsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
//
//    #[ORM\Column(type: 'uuid')]
//    private Uuid $uuid;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isDeleted = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactNo = null;

    #[ORM\Column(nullable: true)]
    private ?int $location = null;

    #[ORM\Column(nullable: true)]
    private ?int $reportingManager = null;

    #[ORM\Column(nullable: true)]
    private ?int $department = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?int $createdBy = null;

    #[ORM\Column(nullable: true)]
    private ?int $deletedBy = null;

    #[ORM\Column(nullable: true)]
    private ?int $updatedBy = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

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
    public function getUserIdentifier(): string
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
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getContactNo(): ?string
    {
        return $this->contactNo;
    }

    public function setContactNo(?string $contactNo): self
    {
        $this->contactNo = $contactNo;

        return $this;
    }

    public function getLocation(): ?int
    {
        return $this->location;
    }

    public function setLocation(?int $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getReportingManager(): ?int
    {
        return $this->reportingManager;
    }

    public function setReportingManager(?int $reportingManager): self
    {
        $this->reportingManager = $reportingManager;

        return $this;
    }

    public function getDepartment(): ?int
    {
        return $this->department;
    }

    public function setDepartment(?int $department): self
    {
        $this->department = $department;

        return $this;
    }
}
