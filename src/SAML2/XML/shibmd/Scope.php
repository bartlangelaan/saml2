<?php

declare(strict_types=1);

namespace SimpleSAML\SAML2\XML\shibmd;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Exception\InvalidDOMElementException;
use SimpleSAML\SAML2\Utils;

/**
 * Class which represents the Scope element found in Shibboleth metadata.
 *
 * @link https://wiki.shibboleth.net/confluence/display/SC/ShibMetaExt+V1.0
 * @package simplesamlphp/saml2
 */
final class Scope extends AbstractShibmdElement
{
    /**
     * The scope.
     *
     * @var string
     */
    protected string $scope;

    /**
     * Whether this is a regexp scope.
     *
     * @var bool
     */
    protected bool $regexp;


    /**
     * Create a Scope.
     *
     * @param string $scope
     * @param bool $regexp
     */
    public function __construct(string $scope, bool $regexp = false)
    {
        $this->setScope($scope);
        $this->setIsRegexpScope($regexp);
    }


    /**
     * Collect the value of the scope-property
     *
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }


    /**
     * Set the value of the scope-property
     *
     * @param string $scope
     */
    private function setScope(string $scope): void
    {
        $this->scope = $scope;
    }


    /**
     * Collect the value of the regexp-property
     *
     * @return bool
     */
    public function isRegexpScope(): bool
    {
        return $this->regexp;
    }


    /**
     * Set the value of the regexp-property
     *
     * @param bool $regexp
     */
    private function setIsRegexpScope(bool $regexp): void
    {
        $this->regexp = $regexp;
    }


    /**
     * Convert XML into a Scope
     *
     * @param \DOMElement $xml The XML element we should load
     * @return self
     *
     * @throws \SimpleSAML\XML\Exception\InvalidDOMElementException if the qualified name of the supplied element is wrong
     */
    public static function fromXML(DOMElement $xml): object
    {
        Assert::same($xml->localName, 'Scope', InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, Scope::NS, InvalidDOMElementException::class);

        $scope = $xml->textContent;
        /** @psalm-var bool $regexp */
        $regexp = self::getBooleanAttribute($xml, 'regexp', 'false');

        return new self($scope, $regexp);
    }


    /**
     * Convert this Scope to XML.
     *
     * @param \DOMElement|null $parent The element we should append this Scope to.
     * @return \DOMElement
     */
    public function toXML(DOMElement $parent = null): DOMElement
    {
        /** @psalm-var \DOMDocument $e->ownerDocument */
        $e = $this->instantiateParentElement($parent);
        $e->appendChild($e->ownerDocument->createTextNode($this->scope));

        if ($this->regexp === true) {
            $e->setAttribute('regexp', 'true');
        } else {
            $e->setAttribute('regexp', 'false');
        }

        return $e;
    }
}
