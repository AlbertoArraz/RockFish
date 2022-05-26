<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Pez::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $pez;

    #[ORM\Column(type: 'integer')]
    #@Assert\notBlank
    #@Assert\GreaterThanOrEqual(1)
    private $cantidad;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private $orderRef;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPez(): ?Pez
    {
        return $this->pez;
    }

    public function setPez(?Pez $pez): self
    {
        $this->pez = $pez;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getOrderRef(): ?Order
    {
        return $this->orderRef;
    }

    public function setOrderRef(?Order $orderRef): self
    {
        $this->orderRef = $orderRef;

        return $this;
    }

    /**
     * Tests if the given item given corresponds to the same order item.
     *
     * @param OrderItem $item
     *
     * @return bool
     */
    public function equals(OrderItem $item): bool
    {
        return $this->getPez()->getId() === $item->getPez()->getId();
    }

     /**
    * Calculates the item total.
    *
    * @return float|int
    */
    public function getTotal(): float
    {
        return $this->getPez()->getPrecio() * $this->getCantidad();
    }
}
