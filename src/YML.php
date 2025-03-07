<?php

namespace LireinCore\YMLParser;

use LireinCore\Exception\FileNotFoundException;
use LireinCore\YMLParser\Exception\ParseException;
use LireinCore\YMLParser\Offer\ArtistTitleOffer;
use LireinCore\YMLParser\Offer\AudioBookOffer;
use LireinCore\YMLParser\Offer\BookOffer;
use LireinCore\YMLParser\Offer\EventTicketOffer;
use LireinCore\YMLParser\Offer\MedicineOffer;
use LireinCore\YMLParser\Offer\SimpleOffer;
use LireinCore\YMLParser\Offer\TourOffer;

class YML
{
    /**
     * @var \XMLReader
     */
    protected $XMLReader;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $schema;

    /**
     * @var array
     */
    protected $pathArr = [];

    /**
     * @var string
     */
    protected $path = '';

    /**
     * @var string
     */
    protected $date;

    /**
     * @var Shop
     */
    protected $shop;

    /**
     * @var int
     */
    protected $openFlags;

    protected $objects = [];

    /**
     * YML constructor.
     */
    public function __construct(int $openFlag = LIBXML_BIGLINES | LIBXML_COMPACT | LIBXML_NOENT | LIBXML_NOERROR)
    {
        $this->objects = [
            'default' => new SimpleOffer(),
            'book' => new BookOffer(),
            'audiobook' => new AudioBookOffer(),
            'artist.title' => new ArtistTitleOffer(),
            'medicine' => new MedicineOffer(),
            'event-ticket' => new EventTicketOffer(),
            'tour' => new TourOffer(),
        ];
        $this->XMLReader = new \XMLReader();
        $this->openFlags = $openFlag;
    }

    /**
     * @param string      $uri
     * @param string|bool $schema
     * @throws \Exception
     */
    public function parse($uri, $schema = true)
    {
        return $this->handleParseErrors(function () use ($uri,$schema) {
            $this->uri = $uri;
            if ($schema === true) {
                $this->schema = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'yml.xsd';
            } elseif (is_string($schema)) {
                $this->schema = $schema;
            }
    
            $this->open();
    
            while ($this->read()) {
                if ($this->path === 'yml_catalog') {
                    $this->date = $this->XMLReader->getAttribute('date');
                    while ($this->read()) {
                        if ($this->path === 'yml_catalog/shop') {
                            $this->shop = $this->parseShop();
                            break;
                        }
                    }
                    break;
                }
            }
    
            $this->close();
        });
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return Shop
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * @return \Generator|SimpleOffer[]|BookOffer[]|AudioBookOffer[]|ArtistTitleOffer[]|MedicineOffer[]|EventTicketOffer[]|TourOffer[]
     * @throws \Exception
     */
    public function getOffers()
    {
        $this->open();

        while ($this->read()) {
            if ($this->path === 'yml_catalog/shop/offers') {
                while ($this->read()) {
                    if ($this->path === 'yml_catalog/shop/offers/offer') {
                        yield $this->parseOffer();
                    } elseif ($this->path === 'yml_catalog/shop') {
                        break;
                    }
                }
                break;
            }
        }

        $this->close();
    }

    /**
     * @return Shop
     * @throws \Exception
     */
    protected function parseShop()
    {
        $xml = $this->XMLReader;
        $shop = new Shop();
        $nodes = [];

        while ($this->read()) {
            if ($this->path === 'yml_catalog/shop/offers') {
                $shop->setOffersCount($this->parseOffersCount());
            } elseif ($xml->nodeType === \XMLReader::ELEMENT) {
                $nodes[] = $this->parseNode('yml_catalog/shop');
            } elseif ($this->path === 'yml_catalog') {
                break;
            }
        }

        $shop->fillShop(['name' => 'shop', 'attributes' => [], 'value' => null, 'nodes' => $nodes]);

        return $shop;
    }

    /**
     * @return SimpleOffer|BookOffer|AudioBookOffer|ArtistTitleOffer|MedicineOffer|EventTicketOffer|TourOffer
     * @throws \Exception
     */
    protected function parseOffer()
    {
        $offerNode = $this->parseNode('yml_catalog/shop/offers');

        $type = $offerNode['attributes']['type'] ?? null;
        if (!$type) {
            $type = null;
        }

        return $this->createOffer($type)->fillOffer($offerNode);

    }

    /**
     * @param string $basePath
     * @return array
     * @throws \Exception
     */
    protected function parseNode($basePath)
    {
        $xml = $this->XMLReader;
        $name = $xml->name;
        $path = $basePath.'/'.$name;
        $value = '';
        $nodes = [];
        $isEmpty = $xml->isEmptyElement;

        $attributes = $this->parseAttributes();

        if (!$isEmpty) {
            while ($this->read()) {
                if ($xml->nodeType === \XMLReader::ELEMENT) {
                    $nodes[] = $this->parseNode($path);
                } elseif (($xml->nodeType === \XMLReader::TEXT || $xml->nodeType === \XMLReader::CDATA) && $xml->hasValue) {
                    $value .= $xml->value;
                } elseif ($this->path === $basePath) {
                    break;
                }
            }
        }
        $value = trim($value);
        if ($value === '') {
            $value = null;
        }

        return ['name' => $name, 'attributes' => $attributes, 'value' => $value, 'nodes' => $nodes];
    }

    /**
     * @return array
     */
    protected function parseAttributes()
    {

            $xml = $this->XMLReader;
            $attributes = [];

            if ($xml->hasAttributes) {
                while ($xml->moveToNextAttribute()) {
                    $attributes[$xml->name] = $xml->value;
                }
            }

            return $attributes;
    
    }

    /**
     * @return int
     * @throws \Exception
     */
    protected function parseOffersCount()
    {
  
            $xml = $this->XMLReader;
            $count = 0;

            while ($this->read()) {
                if ($this->path === 'yml_catalog/shop/offers/offer') {
                    $count++;
                    break;
                }
            }

            while ($xml->next($xml->localName)) {
                $count++;
            }

            return $count;

    }

    /**
     * @throws FileNotFoundException
     */
    protected function open()
    {
        $uri = (string) $this->uri;
        if (!$this->XMLReader->open($uri, null, $this->openFlags)) {
            throw new FileNotFoundException("Failed to open XML file '{$uri}'");
        }

        if (!empty($this->schema)) {
            $schema = $this->schema;
            if (!$this->XMLReader->setSchema($schema)) {
                throw new FileNotFoundException("Failed to open XML Schema file '{$schema}'");
            }
        }
    }

    public function close()
    {
        $this->pathArr = [];
        $this->path = '';
        $this->XMLReader->close();
    }

    /**
     * @return bool
     * @throws \Exception
     */
    protected function read()
    {
 
        $xml = $this->XMLReader;

        if ($xml->read()) {
            if (($this->openFlags & \LIBXML_NOERROR) === 0) {
                $libXmlErrors = \libxml_get_errors();
                if (\count($libXmlErrors) > 0) {
                    throw new ParseException('Parse error', $libXmlErrors[0]);
                }
            }

            if ($xml->nodeType === \XMLReader::ELEMENT && !$xml->isEmptyElement) {
                $this->pathArr[] = $xml->name;
                $this->path = implode('/', $this->pathArr);
            } elseif ($xml->nodeType === \XMLReader::END_ELEMENT) {
                array_pop($this->pathArr);
                $this->path = implode('/', $this->pathArr);
            }

            return true;
        }

        return false;
    }

    /**
     * @param $type
     * @return SimpleOffer|BookOffer|AudioBookOffer|ArtistTitleOffer|MedicineOffer|EventTicketOffer|TourOffer
     */
    protected function createOffer($type)
    {
        if (!isset($this->objects[$type])) {
            return clone $this->objects['default'];
        }
        return clone $this->objects[$type];
    }

    /**
     * @param callable $parse
     * @return mixed
     * @throws ParseException
     */
    protected function handleParseErrors($parse)
    {
        if (($this->openFlags & \LIBXML_NOERROR) === \LIBXML_NOERROR) {
            return $parse();
        }

        $prevErrorHandler = \set_error_handler(null);
        $prevLibxmlUseErrorsFlag = \libxml_use_internal_errors();

        \libxml_use_internal_errors(true);

        try {
            $parseResult = $parse();
        } finally {
            \libxml_clear_errors();
            \libxml_use_internal_errors($prevLibxmlUseErrorsFlag);
            \set_error_handler($prevErrorHandler);
        }

        return $parseResult;
    }
}
