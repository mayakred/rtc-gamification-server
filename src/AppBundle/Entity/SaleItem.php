<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SaleItem.
 *
 * @ORM\Table(name="events__sales_items")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SaleItemRepository")
 */
class SaleItem
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="service", type="string", length=255)
     */
    private $service;

    /**
     * @var bool
     *
     * @ORM\Column(name="new", type="boolean", options={"default" = false})
     */
    private $new = false;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(value="1")
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount = 1;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(value="1")
     *
     * @ORM\Column(name="cost", type="integer")
     */
    private $cost = 1;

    /**
     * @var SaleEvent
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\SaleEvent", inversedBy="items")
     * @ORM\JoinColumn(name="sale_event_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $saleEvent;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set service.
     *
     * @param string $service
     *
     * @return SaleItem
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service.
     *
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param bool $new
     *
     * @return SaleItem
     */
    public function setNew($new)
    {
        $this->new = $new;

        return $this;
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Set amount.
     *
     * @param int $amount
     *
     * @return SaleItem
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount.
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set cost.
     *
     * @param int $cost
     *
     * @return SaleItem
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost.
     *
     * @return int
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->amount * $this->cost;
    }

    /**
     * @param SaleEvent|null $saleEvent
     *
     * @return SaleItem
     */
    public function setSaleEvent($saleEvent = null)
    {
        $this->saleEvent = $saleEvent;

        return $this;
    }

    /**
     * @return SaleEvent
     */
    public function getSaleEvent()
    {
        return $this->saleEvent;
    }
}
