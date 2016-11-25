<?php
/**
 * Crafted with ♥ for developers.
 *
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Expression\Type;

class Each extends AbstractType
{
    /**
     * Parts delimiter.
     */
    const DELIMITER = '#';

    /**
     * @return string
     */
    public function getVerbalString()
    {
        return sprintf(
            'every %s %s',
            $this->getValueWithOrdinalSuffix($this->getSecondPart()->getValue()),
            $this->getFirstPart()->getVerbalString()
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
        if (empty($this->getFirstPart()->getValue())) {
            return [sprintf('Missing first part of "each" expression ("%s")', $this->value)];
        } else {
            return $this->getFirstPart()->getValidationMessages();
        }
    }

    /**
     * @return \string[]
     */
    private function getSecondPartValidationMessages()
    {
        $messages = [];
        $eachValue = $this->getSecondPart()->getValue();
        if (empty($eachValue)) {
            return [sprintf('Missing second part of "each" expression ("%s")', $this->value)];
        } else {
            if (!$this->isInteger($eachValue)) {
                $messages[] = sprintf(
                    'Second part of expression "%s" cannot be "%s", it should be and integer!',
                    $this->value,
                    $eachValue
                );
            }
            if ($eachValue > 5) {
                $messages[] = sprintf(
                    'Second part of expression "%s" cannot be greater than 5 (maximum number of weeks in a month)!',
                    $this->value
                );
            }
        }

        return $messages;
    }
}
