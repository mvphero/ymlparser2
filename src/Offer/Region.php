<?php

namespace LireinCore\YMLParser\Offer;

use LireinCore\YMLParser\TError;
use LireinCore\YMLParser\TYML;

class Region
{
    use TYML;
    use TError;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var bool
     */
    protected $available;

    /**
     * @var
     */
    protected $price;

    /**
     * @var array
     */
    protected $prices;

    /**
     * @var
     */
    protected $oldPrice;

    /**
     * @var int
     */
    protected $count;

    /**
     * @return bool
     */
    public function isValid()
    {
        if (empty($this->id)) {
            $this->addError("Region: missing required attribute 'id'");
        }
        return empty($this->errors);
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function addAttributes($attributes)
    {
        foreach ($attributes as $node) {
            if (strtolower($node['name']) === 'price') {
                $type = mb_strtolower($node['attributes']['type'] ?? null);
                if (!$type || $type === 'default') {
                    $this->setPrice($node['value']);
                } else {
                    $this->addPrices($node['value'], $type);
                }
            } elseif ($node['name'] === 'oldprice') {
                $type = mb_strtolower($node['attributes']['type'] ?? null);
                if (!$type || $type === 'default') {
                    $this->setOldprice($node['value']);
                } else {
                    $this->addOldPrices($node['value'], $type);
                }
            } else {
                $this->addField($node['name'], $node['value']);
            }
        }

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getAvailable(): bool
    {
        if (!is_string($this->available)) {
            return (bool)$this->available;
        }

        $available = str_replace(
            ['1', 'true', 'yes', '0', 'false', 'no'],
            [true, true, true, false, false, false],
            $this->available
        );

        return (bool)$available;
    }

    public function setAvailable($value): void
    {
        $this->available = $value;
    }

    /**
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     */
    public function getOldPrice()
    {
        return $this->oldPrice;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @param mixed $oldPrice
     */
    public function setOldPrice($oldPrice)
    {
        $this->oldPrice = $oldPrice;
    }

    public function setPresence($presence){
        $this->setAvailable($presence);
    }

    public function getPresence(): bool
    {
        return $this->available === 'true';
    }

    public function addPrices($price, $type)
    {
        $this->prices[$type]['price'] = (float) $price;
    }

    public function addOldPrices($price, $type)
    {
        $this->prices[$type]['oldPrice'] = (float) $price;
    }

    /**
     * @return array
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param mixed $count
     */
    public function setCount($count): void
    {
        $this->count = $count;
    }
}
