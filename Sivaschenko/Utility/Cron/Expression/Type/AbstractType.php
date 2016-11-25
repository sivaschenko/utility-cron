<?php
/**
 * Crafted with ♥ for developers.
 *
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Expression\Type;

use Sivaschenko\Utility\Cron\Expression\Part\Part;
use Sivaschenko\Utility\Cron\Expression\PartInterface;
use Sivaschenko\Utility\Cron\Expression\TypeInterface;

abstract class AbstractType implements TypeInterface
{
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
     * @param string $name
     */
    public function __construct($value, $name)
    {
        $this->value = $value;
        if (!empty(static::DELIMITER)) {
            foreach (explode(static::DELIMITER, $value) as $part) {
                $this->parts[] = new Part($name, $part);
            }
        } else {
            $this->parts[] = new Part($name, $value);
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
        $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];

        return (($value % 100) >= 11 && ($value % 100) <= 13)
            ? $value.'th'
            : $value.$ends[$value % 10];
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
