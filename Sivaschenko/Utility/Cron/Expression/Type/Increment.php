<?php
/**
 * Crafted with ♥ for developers.
 *
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Expression\Type;

class Increment extends AbstractType
{
    /**
     * Parts delimiter.
     */
    const DELIMITER = '/';

    /**
     * @return string
     */
    public function getVerbalString()
    {
        $string = sprintf(
            'every %s %s',
            $this->getValueWithOrdinalSuffix($this->getSecondPart()->getValue()),
            $this->getFirstPart()->getName()
        );
        if (!in_array($this->getFirstPart()->getValue(), ['*', '?'])) {
            $string .= ' starting from '.$this->getFirstPart()->getVerbalString();
        }

        return $string;
    }

    /**
     * @return \string[]
     */
    public function getValidationMessages()
    {
        return array_merge($this->getFirstPartValidationMessages(), $this->getSecondPartValidationMessages());
    }

    /**
     * @return \string[]
     */
    private function getFirstPartValidationMessages()
    {
        if (empty($this->getFirstPart()->getValue())) {
            return [sprintf('Missing first part of "increment" expression ("%s")', $this->value)];
        } else {
            return $this->getFirstPart()->getValidationMessages();
        }
    }

    /**
     * @return \string[]
     */
    private function getSecondPartValidationMessages()
    {
        if (empty($this->getSecondPart()->getValue())) {
            return [sprintf('Missing second part of "increment" expression ("%s")', $this->value)];
        } elseif (!$this->isInteger($this->getSecondPart()->getValue())) {
            return [sprintf('Second part of expression "%s" can only be integer!', $this->value)];
        }

        return [];
    }
}
