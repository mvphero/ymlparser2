<?php

namespace LireinCore\YMLParser\Offer;

use LireinCore\YMLParser\TError;
use LireinCore\YMLParser\TYML;

class Stock
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
     * @return bool
     */
    public function isValid()
    {
        if (empty($this->id)) {
            $this->addError("Stock: missing required attribute 'id'");
        }
        return empty($this->errors);
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function addAttributes($attributes)
    {
        foreach ($attributes as $name => $value) {
            $this->addField($name, $value);
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
        return $this->available === 'true';
    }

    public function setAvailable($value): void
    {
        $this->available = $value;
    }
}