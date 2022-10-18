<?php

namespace App\Entity;

use App\Repository\AssetsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssetsRepository::class)]
class Assets
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $productCategory = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $productType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $product = null;

    #[ORM\Column(nullable: true)]
    private ?int $vendor = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $assetName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $seriulNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descriptionType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $purchaseDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $warrantyExpiryDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $purchaseType = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $usefulLife = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $residualValue = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rate = null;

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

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct(?string $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getVendor(): ?int
    {
        return $this->vendor;
    }

    public function setVendor(?int $vendor): self
    {
        $this->vendor = $vendor;

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

    public function getSeriulNumber(): ?string
    {
        return $this->seriulNumber;
    }

    public function setSeriulNumber(?string $seriulNumber): self
    {
        $this->seriulNumber = $seriulNumber;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescriptionType(): ?string
    {
        return $this->descriptionType;
    }

    public function setDescriptionType(?string $descriptionType): self
    {
        $this->descriptionType = $descriptionType;

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

    public function getPurchaseDate(): ?\DateTimeImmutable
    {
        return $this->purchaseDate;
    }

    public function setPurchaseDate(?\DateTimeImmutable $purchaseDate): self
    {
        $this->purchaseDate = $purchaseDate;

        return $this;
    }

    public function getWarrantyExpiryDate(): ?\DateTimeImmutable
    {
        return $this->warrantyExpiryDate;
    }

    public function setWarrantyExpiryDate(?\DateTimeImmutable $warrantyExpiryDate): self
    {
        $this->warrantyExpiryDate = $warrantyExpiryDate;

        return $this;
    }

    public function getPurchaseType(): ?string
    {
        return $this->purchaseType;
    }

    public function setPurchaseType(?string $purchaseType): self
    {
        $this->purchaseType = $purchaseType;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUsefulLife(): ?string
    {
        return $this->usefulLife;
    }

    public function setUsefulLife(?string $usefulLife): self
    {
        $this->usefulLife = $usefulLife;

        return $this;
    }

    public function getResidualValue(): ?string
    {
        return $this->residualValue;
    }

    public function setResidualValue(?string $residualValue): self
    {
        $this->residualValue = $residualValue;

        return $this;
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function setRate(?string $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?int $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedBy(): ?int
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?int $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getDeletedBy(): ?int
    {
        return $this->deletedBy;
    }

    public function setDeletedBy(?int $deletedBy): self
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function isIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(?bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }
}
