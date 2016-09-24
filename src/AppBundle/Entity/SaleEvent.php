<?php

namespace AppBundle\Entity;

use AppBundle\DBAL\Types\EventType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="events__sales")
 * @ORM\Entity
 */
class SaleEvent extends Event
{
    /**
     * @var int
     *
     * @ORM\Column(name="total", type="integer", nullable=false)
     */
    private $total = 0;

    /**
     * @var SaleItem
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SaleItem", mappedBy="saleEvent", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $items;

    /**
     * SaleEvent constructor.
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * @param int $total
     *
     * @return SaleEvent
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * {@inheritdoc}
     */
    public function getEventType()
    {
        return EventType::SALE;
    }

    /**
     * @param SaleItem $item
     *
     * @return SaleEvent
     */
    public function addItem(SaleItem $item)
    {
        $this->total += $item->getTotal();

        $item->setSaleEvent($this);
        $this->items[] = $item;

        return $this;
    }

    /**
     * @param SaleItem $item
     *
     * @return SaleEvent
     */
    public function removeItem(SaleItem $item)
    {
        $item->setSaleEvent(null);
        $this->items->removeElement($item);

        return $this;
    }

    /**
     * @return SaleItem|ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }
}
