<?php

namespace App\Entity;

use App\Common\CommonMethodsTrait\DeletedMethodsTraits\DeletedMethodsTrait;
use App\Common\CommonMethodsTrait\UserMethodsTraits\UserMethodsTrait;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartmentRepository::class)]
class Department
{
    use DeletedMethodsTrait;
    use UserMethodsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $departmentName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactPerson = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactPersonEmail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactPersonPhone = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isDeleted = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?int $createdBy = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $updatedBy = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $deletedBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepartmentName(): ?string
    {
        return $this->departmentName;
    }

    public function setDepartmentName(?string $departmentName): self
    {
        $this->departmentName = $departmentName;

        return $this;
    }

    public function getContactPerson(): ?string
    {
        return $this->contactPerson;
    }

    public function setContactPerson(?string $contactPerson): self
    {
        $this->contactPerson = $contactPerson;

        return $this;
    }

    public function getContactPersonEmail(): ?string
    {
        return $this->contactPersonEmail;
    }

    public function setContactPersonEmail(?string $contactPersonEmail): self
    {
        $this->contactPersonEmail = $contactPersonEmail;

        return $this;
    }

    public function getContactPersonPhone(): ?string
    {
        return $this->contactPersonPhone;
    }

    public function setContactPersonPhone(?string $contactPersonPhone): self
    {
        $this->contactPersonPhone = $contactPersonPhone;

        return $this;
    }
}
