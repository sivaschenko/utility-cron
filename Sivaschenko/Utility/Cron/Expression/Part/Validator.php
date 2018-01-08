<?php
/**
 * Crafted with ♥ for developers.
 *
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */

namespace Sivaschenko\Utility\Cron\Expression\Part;

class Validator
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
     *
     * @return bool
     */
    public function isValid($value)
    {
        return empty($this->getValidationMessages($value));
    }

    /**
     * @param string $value
     *
     * @return string[]
     */
    public function getValidationMessages($value)
    {
        $messages = [];
        if (in_array($value, $this->getSpecialValues())) {
            return $messages;
        }

        if (is_numeric($value)) {
            if ($value > $this->getMaxValue()) {
                $messages[] = sprintf(
                    '%s expression part value "%s" is greater than max allowed "%s"',
                    ucfirst($this->name),
                    $value,
                    $this->getMaxValue()
                );
            }
            if ($value < $this->getMinValue()) {
                $messages[] = sprintf(
                    '%s expression part value "%s" is less than min allowed "%s"',
                    ucfirst($this->name),
                    $value,
                    $this->getMinValue()
                );
            }
        } else {
            if (!in_array(strtolower($value), $this->getStringValues())) {
                $message = sprintf(
                    '%s expression part value "%s" is not valid! Allowed values are: %s.',
                    ucfirst($this->name),
                    $value,
                    $this->getAllowedValuesMessage()
                );
                if (!empty($this->getSuffixes())) {
                    $message .= sprintf(' Allowed suffixes are: "%s".', implode('", "', $this->getSuffixes()));
                }
                $messages[] = $message;
            }
        }

        return $messages;
    }

    /**
     * @return string
     */
    private function getAllowedValuesMessage()
    {
        $messages[] = sprintf('numbers from %s to %s', $this->getMinValue(), $this->getMaxValue());

        if (!empty($this->getSpecialValues())) {
            $messages[] = sprintf('special values: "%s"', implode('", "', $this->getSpecialValues()));
        }

        if (!empty($this->getStringValues())) {
            $messages[] = sprintf('string values: "%s"', implode('", "', $this->getStringValues()));
        }

        return implode('; ', $messages);
    }

    /**
     * @return int
     */
    private function getMinValue()
    {
        return $this->config->getData($this->name, 'min');
    }

    /**
     * @return int
     */
    private function getMaxValue()
    {
        return $this->config->getData($this->name, 'max');
    }

    /**
     * @return \string[]
     */
    private function getStringValues()
    {
        return $this->config->getData($this->name, 'values');
    }

    /**
     * @return \string[]
     */
    private function getSuffixes()
    {
        return array_keys($this->config->getData($this->name, 'suffixes'));
    }

    /**
     * @return \string[]
     */
    private function getSpecialValues()
    {
        return array_keys($this->config->getData($this->name, 'special_values'));
    }
}
