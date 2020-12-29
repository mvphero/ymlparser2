<?php

namespace LireinCore\YMLParser\Offer;

class SimpleOffer extends AMainOffer
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $typePrefix;

    /**
     * @return array
     */
    public function getAttributesList()
    {
        return array_merge(parent::getAttributesList(), [
            //subNodes
            'name', 'typePrefix'
        ]);
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $isValid = parent::isValid();

        if ($this->name === null) {
            $this->addError("Offer: missing required attribute 'name'");
        }

        return $isValid && empty($this->errors);
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setName($value)
    {
        $this->name = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTypePrefix()
    {
        return $this->typePrefix;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setTypePrefix($value)
    {
        $this->typePrefix = $value;

        return $this;
    }
}