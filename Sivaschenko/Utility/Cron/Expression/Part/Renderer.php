<?php
/**
 * Crafted with ♥ for developers
 *
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Expression\Part;

class Renderer
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param string $name
     * @param Config $config
     */
    public function __construct($name, Config $config)
    {
        $this->name = $name;
        $this->config = $config;
    }

    /**
     * @param string $value
     * @param string $suffix
     * @return string
     */
    public function getVerbalString($value, $suffix)
    {
        $replacements = $this->getSpecialValues();
        $suffixes = $this->getSuffixes();
        if (isset($replacements[$value])) {
            return $replacements[$value];
        }
        if ($suffix) {
            return sprintf($suffixes[$suffix], $this->render($value));
        }

        return $this->render($value);
    }

    /**
     * @param string $value
     * @return string
     */
    private function render($value)
    {
        switch ($this->name) {
            case 'minute':
            case 'hour':
            case 'day of month':
                return $this->getValueWithOrdinalSuffix($value);
            case 'month':
                return $this->getMonthVerbalString($value);
            case 'day of week':
                return $this->getDayOfWeekVerbalString($value);
            case 'year':
                return $value;
            default:
                return $value;
        }
    }

    /**
     * @param string $value
     * @return string
     */
    private function getDayOfWeekVerbalString($value)
    {
        $date = \DateTime::createFromFormat('D', $value);
        if ($date) {
            return $date->format('l');
        }
        $string = date('l', strtotime("Sunday + $value Days"));

        return $string ? $string : $value;
    }

    /**
     * @param string $value
     * @return string
     */
    private function getMonthVerbalString($value)
    {
        $date = \DateTime::createFromFormat('M', $value);
        if ($date) {
            return $date->format('F');
        }

        $number = $value - 1;
        $string = date('F', strtotime("January + $number Months"));

        return $string ? $string : $value;
    }

    /**
     * @param string $value
     * @return string
     */
    private function getValueWithOrdinalSuffix($value)
    {
        $value = (int) $value;
        $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];

        return (($value % 100) >= 11 && ($value % 100) <= 13)
            ? $value.'th'
            : $value.$ends[$value % 10];
    }

    /**
     * @return \string[]
     */
    private function getSuffixes()
    {
        return $this->config->getData($this->name, 'suffixes');
    }

    /**
     * @return \string[]
     */
    private function getSpecialValues()
    {
        return $this->config->getData($this->name, 'special_values');
    }
}
