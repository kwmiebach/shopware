<?php declare(strict_types=1);

namespace Shopware\Api\Order\Event\OrderDelivery;

use Shopware\Api\Order\Collection\OrderDeliveryDetailCollection;
use Shopware\Api\Order\Event\Order\OrderBasicLoadedEvent;
use Shopware\Api\Order\Event\OrderAddress\OrderAddressBasicLoadedEvent;
use Shopware\Api\Order\Event\OrderDeliveryPosition\OrderDeliveryPositionBasicLoadedEvent;
use Shopware\Api\Order\Event\OrderState\OrderStateBasicLoadedEvent;
use Shopware\Api\Shipping\Event\ShippingMethod\ShippingMethodBasicLoadedEvent;
use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Event\NestedEvent;
use Shopware\Framework\Event\NestedEventCollection;

class OrderDeliveryDetailLoadedEvent extends NestedEvent
{
    const NAME = 'order_delivery.detail.loaded';

    /**
     * @var TranslationContext
     */
    protected $context;

    /**
     * @var OrderDeliveryDetailCollection
     */
    protected $orderDeliveries;

    public function __construct(OrderDeliveryDetailCollection $orderDeliveries, TranslationContext $context)
    {
        $this->context = $context;
        $this->orderDeliveries = $orderDeliveries;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): TranslationContext
    {
        return $this->context;
    }

    public function getOrderDeliveries(): OrderDeliveryDetailCollection
    {
        return $this->orderDeliveries;
    }

    public function getEvents(): ?NestedEventCollection
    {
        $events = [];
        if ($this->orderDeliveries->getOrders()->count() > 0) {
            $events[] = new OrderBasicLoadedEvent($this->orderDeliveries->getOrders(), $this->context);
        }
        if ($this->orderDeliveries->getShippingAddress()->count() > 0) {
            $events[] = new OrderAddressBasicLoadedEvent($this->orderDeliveries->getShippingAddress(), $this->context);
        }
        if ($this->orderDeliveries->getOrderStates()->count() > 0) {
            $events[] = new OrderStateBasicLoadedEvent($this->orderDeliveries->getOrderStates(), $this->context);
        }
        if ($this->orderDeliveries->getShippingMethods()->count() > 0) {
            $events[] = new ShippingMethodBasicLoadedEvent($this->orderDeliveries->getShippingMethods(), $this->context);
        }
        if ($this->orderDeliveries->getPositions()->count() > 0) {
            $events[] = new OrderDeliveryPositionBasicLoadedEvent($this->orderDeliveries->getPositions(), $this->context);
        }

        return new NestedEventCollection($events);
    }
}