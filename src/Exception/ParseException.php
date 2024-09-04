<?php

namespace LireinCore\YMLParser\Exception;

use Exception;
use LibXMLError;

class ParseException extends Exception
{
    /**
     * @var string|null
     */
    private $brokenFragment;

    /**
     * @var LibXMLError|null
     */
    private $xmlError;

    /**
     * @param string $message
     * @param LibXMLError|null $xmlError
     * @param int $code
     */
    public function __construct(
        $message = "",
        $xmlError = null,
        $brokenFragment = null,
        $code = 0
    ) {
        $this->xmlError = $xmlError;
        $this->brokenFragment = $brokenFragment;

        parent::__construct($message, $code);
    }

    /**
     * @var string|null
     */
    public function getBrokenFragment()
    {
        return $this->brokenFragment;
    }

    /***
     * @return LibXMLError|null
     */
    public function getXmlError()
    {
        return $this->xmlError;
    }
}
