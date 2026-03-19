<?php declare(strict_types=1);

namespace Vic\ProductInquiry\Core\Content\Inquiry;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class InquiryEntity extends Entity
{
    use EntityIdTrait;

    protected string $productId = '';
    protected string $productName = '';
    protected string $customerName = '';
    protected string $customerEmail = '';
    protected ?string $message = null;
    protected ?\DateTimeInterface $startDate = null;
    protected ?\DateTimeInterface $endDate = null;
    protected ?int $rentalDays = null;
    protected ?float $totalPrice = null;

    public function getProductId(): string { return $this->productId; }
    public function setProductId(string $productId): void { $this->productId = $productId; }

    public function getProductName(): string { return $this->productName; }
    public function setProductName(string $productName): void { $this->productName = $productName; }

    public function getCustomerName(): string { return $this->customerName; }
    public function setCustomerName(string $customerName): void { $this->customerName = $customerName; }

    public function getCustomerEmail(): string { return $this->customerEmail; }
    public function setCustomerEmail(string $customerEmail): void { $this->customerEmail = $customerEmail; }

    public function getMessage(): ?string { return $this->message; }
    public function setMessage(?string $message): void { $this->message = $message; }

    public function getStartDate(): ?\DateTimeInterface { return $this->startDate; }
    public function setStartDate(?\DateTimeInterface $startDate): void { $this->startDate = $startDate; }

    public function getEndDate(): ?\DateTimeInterface { return $this->endDate; }
    public function setEndDate(?\DateTimeInterface $endDate): void { $this->endDate = $endDate; }

    public function getRentalDays(): ?int { return $this->rentalDays; }
    public function setRentalDays(?int $rentalDays): void { $this->rentalDays = $rentalDays; }

    public function getTotalPrice(): ?float { return $this->totalPrice; }
    public function setTotalPrice(?float $totalPrice): void { $this->totalPrice = $totalPrice; }
}
