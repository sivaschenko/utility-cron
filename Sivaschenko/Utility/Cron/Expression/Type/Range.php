<?php
/**
 * Crafted with ♥ for developers.
 *
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */

namespace Sivaschenko\Utility\Cron\Expression\Type;

class Range extends AbstractType
{
    /**
     * Parts delimiter.
     */
    const DELIMITER = '-';

    /**
     * @return string
     */
    public function getVerbalString()
    {
        return sprintf(
            'every %s from %s through %s',
            $this->getFirstPart()->getName(),
            $this->getFirstPart()->getVerbalString(),
            $this->getSecondPart()->getVerbalString()
        );
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
        $messages = [];
        if (empty($this->getFirstPart()->getValue())) {
            $messages[] = sprintf('Missing first part of "range" expression ("%s")', $this->value);
        } else {
            if ($this->getFirstPart()->getValue() == 'W') {
                $messages[] = sprintf('Unexpected value "W" (weekdays) in range expression ("%s")!', $this->value);
            }
            $messages = array_merge($messages, $this->getFirstPart()->getValidationMessages());
        }

        return $messages;
    }

    /**
     * @return \string[]
     */
    private function getSecondPartValidationMessages()
    {
        $messages = [];
        if (empty($this->getSecondPart()->getValue())) {
            $messages[] = sprintf('Missing second part of "range" expression ("%s")', $this->value);
        } else {
            if ($this->getSecondPart()->getValue() == 'W') {
                $messages[] = sprintf('Unexpected value "W" (weekdays) in range expression ("%s")!', $this->value);
            }
            $messages = array_merge($messages, $this->getSecondPart()->getValidationMessages());
        }

        return $messages;
    }
}
