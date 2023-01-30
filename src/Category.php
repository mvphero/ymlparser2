<?php

namespace LireinCore\YMLParser;

class Category
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
    protected $parentId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var integer
     */
    private $ordering;

    /**
     * @var array
     */
    public $children = [];

    /**
     * @return bool
     */
    public function isValid()
    {
        if ($this->id === null) {
            $this->addError("Category: missing required attribute 'id'");
        } elseif (!is_numeric($this->id) || (int)$this->id <= 0) {
            $this->addError("Category: incorrect value in attribute 'id'");
        }
        if ($this->parentId !== null && (!is_numeric($this->parentId) || (int)$this->parentId <= 0)) {
            $this->addError("Category: incorrect value in attribute 'parentId'");
        }
        if (!$this->name) {
            $this->addError('Category: incorrect value');
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
     * @return string|null
     */
    public function getId()
    {
        return $this->id === null ? null : $this->id;
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
     * @return int|null
     */
    public function getParentId()
    {
        return $this->parentId === null ? null : (int)$this->parentId;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setParentId($value)
    {
        $this->parentId = $value;

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

    /**
     * @return null|int
     */
    public function getOrdering(): ?int
    {
        return $this->ordering;
    }

    /**
     * @param int $ordering
     *
     * @return self
     */
    public function setOrdering(int $ordering): self
    {
        $this->ordering = $ordering;

        return $this;
    }
}