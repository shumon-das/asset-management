<?php

namespace App\Entity;

use App\Common\CommonMethodsTrait\DeletedMethodsTraits\DeletedMethodsTrait;
use App\Common\CommonMethodsTrait\DescriptionMethodsTrait;
use App\Common\CommonMethodsTrait\StatusMethodsTrait;
use App\Common\CommonMethodsTrait\UserMethodsTraits\UserMethodsTrait;
use App\Repository\AssigningAssetsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AssigningAssetsRepository::class)]
class AssigningAssets
{
    use DeletedMethodsTrait;
    use StatusMethodsTrait;
    use UserMethodsTrait;
    use DescriptionMethodsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $productCategory = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $productType = null;

    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank]
    private string $product;

    #[ORM\Column(nullable: false)]
    #[Assert\NotBlank]
    private int $vendor;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank]
    private string $assetName;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $department = null;

    #[ORM\Column(nullable: false)]
    #[Assert\NotBlank]
    private int $assignTo;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $assignComponent = null;

    #[ORM\Column(nullable: true)]
    private ?int $createdBy = null;

    #[ORM\Column(nullable: true)]
    private ?int $updatedBy = null;

    #[ORM\Column(nullable: true)]
    private ?int $deletedBy = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $status = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isDeleted = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductCategory(): ?string
    {
        return $this->productCategory;
    }

    public function setProductCategory(?string $productCategory): self
    {
        $this->productCategory = $productCategory;

        return $this;
    }

    public function getProductType(): ?string
    {
        return $this->productType;
    }

    public function setProductType(?string $productType): self
    {
        $this->productType = $productType;

        return $this;
    }

    public function getProduct(): string
    {
        return $this->product;
    }

    public function setProduct(string $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getVendor(): int
    {
        return $this->vendor;
    }

    public function setVendor(int $vendor): self
    {
        $this->vendor = $vendor;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getAssetName(): ?string
    {
        return $this->assetName;
    }

    public function setAssetName(?string $assetName): self
    {
        $this->assetName = $assetName;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getAssignTo(): int
    {
        return $this->assignTo;
    }

    public function setAssignTo(int $assignTo): self
    {
        $this->assignTo = $assignTo;

        return $this;
    }

    public function getAssignComponent(): ?string
    {
        return $this->assignComponent;
    }

    public function setAssignComponent(?string $assignComponent): self
    {
        $this->assignComponent = $assignComponent;

        return $this;
    }
}
