<?php
/**
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Expression\Type;

use Sivaschenko\Utility\Cron\Expression\PartInterface;
use Sivaschenko\Utility\Cron\Expression\TypeInterface;

abstract class AbstractType implements TypeInterface
{
    /**
     * @var string|PartInterface
     */
    protected $partClass;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $partName;

    /**
     * @var string
     */
    protected $partPrefix;

    /**
     * @var PartInterface[]
     */
    protected $parts = [];

    /**
     * AbstractType constructor.
     *
     * @param string $value
     * @param string $partClass
     */
    public function __construct($value, $partClass)
    {
        $this->value = $value;
        $this->partClass = $partClass;
        if (!empty(static::DELIMITER)) {
            foreach (explode(static::DELIMITER, $value) as $part) {
                $this->parts[] = new $partClass($part);
            }
        } else {
            $this->parts[] = new $partClass($value);
        }
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this::isApplicable($this->value) && empty($this->getValidationMessages());
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isApplicable($value)
    {
        if (empty(static::DELIMITER)) {
            return true;
        }

        return false !== strpos($value, static::DELIMITER);
    }

    /**
     * @return array
     */
    public function getValidationMessages()
    {
        $messages = [];
        foreach ($this->parts as $part) {
            $messages = array_merge($messages, $part->getValidationMessages());
        }

        return $messages;
    }

    /**
     * @param string|int $value
     *
     * @return bool
     */
    protected function isInteger($value)
    {
        return strval($value) == strval(intval($value));
    }

    /**
     * @param int $value
     *
     * @return string
     */
    protected function getValueWithOrdinalSuffix($value)
    {
        return \DateTime::createFromFormat('j', (int) $value)->format('jS');
    }

    /**
     * @return null|PartInterface
     */
    protected function getFirstPart()
    {
        return isset($this->parts[0]) ? $this->parts[0] : null;
    }

    /**
     * @return null|PartInterface
     */
    protected function getSecondPart()
    {
        return isset($this->parts[1]) ? $this->parts[1] : null;
    }
}
