<?php

namespace LireinCore\Exception;

use LibXMLError;

class ParseException extends AException
{
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
        $code = 0
    ) {
        $this->xmlError = $xmlError;

        parent::__construct($message, $code);
    }

    /***
     * @return LibXMLError|null
     */
    public function getXmlError()
    {
        return $this->xmlError;
    }
}
