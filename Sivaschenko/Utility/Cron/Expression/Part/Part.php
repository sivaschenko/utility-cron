<?php
/**
 * Crafted with ♥ for developers.
 *
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Expression\Part;

use Sivaschenko\Utility\Cron\Expression\PartInterface;

class Part implements PartInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * Expression part (without suffix).
     *
     * @var string
     */
    private $value;

    /**
     * Expression value suffix.
     *
     * @var string
     */
    private $suffix;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param string $name
     * @param string $value
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        foreach ($this->getSuffixes() as $suffix) {
            $valueLength = strlen($value);
            $suffixLength = strlen($suffix);
            if (($valueLength > $suffixLength) && substr($value, -$suffixLength) === $suffix) {
                $this->suffix = $suffix;
                $this->value = substr($value, 0, $valueLength - $suffixLength);
            }
        }
        if (null === $this->value) {
            $this->value = $value;
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getVerbalString()
    {
        if (!$this->renderer) {
            $this->renderer = new Renderer($this->name, $this->getConfig());
        }

        return $this->renderer->getVerbalString($this->getValue(), $this->suffix);
    }

    /**
     * @return \string[]
     */
    public function getValidationMessages()
    {
        if (!$this->validator) {
            $this->validator = new Validator($this->name, $this->getConfig());
        }

        return $this->validator->getValidationMessages($this->getValue());
    }

    /**
     * @return Config
     */
    private function getConfig()
    {
        if (!$this->config) {
            $this->config = new Config();
        }

        return $this->config;
    }

    /**
     * @return string
     */
    public function getSuffix()
    {
        return ('W' == $this->value) ? '' : $this->getConfig()->getData($this->getName(), 'suffix');
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->getConfig()->getData($this->getName(), 'prefix');
    }

    /**
     * @return array
     */
    private function getSuffixes()
    {
        return array_keys($this->getConfig()->getData($this->getName(), 'suffixes'));
    }
}
