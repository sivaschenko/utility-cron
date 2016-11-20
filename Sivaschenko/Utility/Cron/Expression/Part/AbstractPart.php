<?php
/**
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Expression\Part;

use Sivaschenko\Utility\Cron\Expression\PartInterface;

abstract class AbstractPart implements PartInterface
{
    /**
     * Expression that can be used instead of part value.
     *
     * @var string[]
     */
    protected $replacements = ['*' => 'every', '?' => 'any'];

    /**
     * Suffixes that can be added to expression value.
     *
     * @var string[]
     */
    protected $valueSuffixes = [];

    /**
     * Minimum valid integer value for expression part.
     *
     * @var int
     */
    protected $minValue;

    /**
     * Maximum valid integer value for expression part.
     *
     * @var int
     */
    protected $maxValue;

    /**
     * Expression part (without suffix).
     *
     * @var string
     */
    protected $value;

    /**
     * Expression value suffix.
     *
     * @var string
     */
    protected $valueSuffix;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        foreach (array_keys($this->valueSuffixes) as $suffix) {
            $valueLength = strlen($value);
            $suffixLength = strlen($suffix);
            if (($valueLength > $suffixLength) && substr($value, -$suffixLength) === $suffix) {
                $this->valueSuffix = $suffix;
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
    public function getVerbalString()
    {
        if (isset($this->replacements[$this->value])) {
            return $this->replacements[$this->value];
        }
        if ($this->valueSuffix) {
            return sprintf($this->valueSuffixes[$this->valueSuffix], $this->_getVerbalString());
        }

        return $this->_getVerbalString();
    }

    /**
     * @return string
     */
    protected function _getVerbalString()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string[]
     */
    public function getValidationMessages()
    {
        $messages = [];
        $replacements = array_keys($this->replacements);
        $suffixes = array_keys($this->valueSuffixes);
        if (in_array($this->value, $replacements)) {
            return $messages;
        }

        if (is_numeric($this->value)) {
            if ($this->value > static::MAX_INT_VALUE) {
                $messages[] = sprintf(
                    '%s expression part value "%s" is greater than max allowed "%s"',
                    ucfirst(static::NAME),
                    $this->value,
                    static::MAX_INT_VALUE
                );
            }
            if ($this->value < static::MIN_INT_VALUE) {
                $messages[] = sprintf(
                    '%s expression part value "%s" is less than min allowed "%s"',
                    ucfirst(static::NAME),
                    $this->value,
                    static::MIN_INT_VALUE
                );
            }
        } else {
            if (!in_array(strtolower($this->value), $this->getAllowedStringValues())) {
                $messages[] = sprintf(
                    '%s expression part value "%s" is not valid! Allowed values are integers from %s to %s%s%s%s',
                    ucfirst(static::NAME),
                    $this->value,
                    static::MIN_INT_VALUE,
                    static::MAX_INT_VALUE,
                    empty($replacements) ? '' : ', keywords: "'.implode('", "', $replacements).'"',
                    empty($this->getAllowedStringValues()) ? '' : ' or string values: "'.implode('", "', $this->getAllowedStringValues()).'".',
                    empty($suffixes) ? '' : ' Allowed suffixes: "'.implode('", "', $suffixes).'"'
                );
            }
        }

        return $messages;
    }

    /**
     * @return string[]
     */
    protected function getAllowedStringValues()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getValueWithOrdinalSuffix()
    {
        $value = (int) $this->value;
        $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];

        return (($value % 100) >= 11 && ($value % 100) <= 13)
            ? $value.'th'
            : $value.$ends[$value % 10];
    }

    public function getName()
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public function getSuffix()
    {
        return static::SUFFIX ? ' '.static::SUFFIX : '';
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return static::PREFIX ? static::PREFIX.' ' : '';
    }
}
