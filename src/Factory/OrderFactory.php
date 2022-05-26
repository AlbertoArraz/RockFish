<?php

namespace App\Factory;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Pez;

/**
 * Class OrderFactory
 * @package App\Factory
 */
class OrderFactory
{
    /**
     * Creates an order.
     *
     * @return Order
     */
    public function create(): Order
    {
        $order = new Order();
        $order
            ->setStatus(Order::STATUS_CART)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        return $order;
    }

    /**
     * Creates an item for a pez.
     *
     * @param Pez $pez
     *
     * @return OrderItem
     */
    public function createItem(Pez $pez): OrderItem
    {
        $item = new OrderItem();
        $item->setPez($pez);
        $item->setCantidad(1);

        return $item;
    }
}
