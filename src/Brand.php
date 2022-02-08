<?php

namespace LireinCore\YMLParser;

class Brand
{
    use TYML;
    use TError;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $url;

    /**
     * @return bool
     */
    public function isValid()
    {
        if ($this->id === null) {
            $this->addError("Brand: missing required attribute 'id'");
        } elseif (!is_numeric($this->id) || (int)$this->id <= 0) {
            $this->addError("Brand: incorrect value in attribute 'id'");
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

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id === null ? null : (int)$this->id;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setId($value)
    {
        $this->id = $value;

        return $this;
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
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setUrl($value)
    {
        $this->url = $value;

        return $this;
    }
}
