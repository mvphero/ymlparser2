<?php

namespace LireinCore\YMLParser;

class Brand
{
    use TYML;
    use TError;

    /**
     * @var string|null
     */
    protected $id = null;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string|null
     */
    private $logo;

    /**
     * @return bool
     */
    public function isValid()
    {
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
     * @return string|null
     */
    public function getId()
    {
        return is_null($this->id) ? $this->getName() : $this->id;
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

    /**
     * @return string|null
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string|null $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @param string|null $id
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
