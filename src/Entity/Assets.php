<?php

namespace App\Entity;

use App\Common\CommonMethodsTrait\DeletedMethodsTraits\DeletedMethodsTrait;
use App\Common\CommonMethodsTrait\DescriptionMethodsTrait;
use App\Common\CommonMethodsTrait\StatusMethodsTrait;
use App\Common\CommonMethodsTrait\UserMethodsTraits\UserMethodsTrait;
use App\Repository\AssetsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AssetsRepository::class)]
class Assets
{
    use DeletedMethodsTrait;
    use UserMethodsTrait;
    use StatusMethodsTrait;
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

    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank]
    private string $assetName;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $serialNumber = null;

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

    public function getAssetName(): string
    {
        return $this->assetName;
    }

    public function setAssetName(string $assetName): self
    {
        $this->assetName = $assetName;

        return $this;
    }

    public function getSerialNumber(): ?string
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(?string $serialNumber): self
    {
        $this->serialNumber = $serialNumber;

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
}
