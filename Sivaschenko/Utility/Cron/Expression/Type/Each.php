<?php
/**
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Expression\Type;

class Each extends AbstractType
{
    const DELIMITER = '#';

    /**
     * @return string
     */
    public function getVerbalString()
    {
        return sprintf(
            'every %s %s',
            $this->getSecondPart()->getValueWithOrdinalSuffix(),
            $this->getFirstPart()->getVerbalString()
        );
    }

    /**
     * @return \string[]
     */
    public function getValidationMessages()
    {
        $messages = [];
        if (empty($this->getFirstPart()->getValue())) {
            $messages[] = sprintf('Missing first part of "each" expression ("%s")', $this->value);
        } else {
            $messages = array_merge($messages, $this->getFirstPart()->getValidationMessages());
        }
        $eachValue = $this->getSecondPart()->getValue();
        if (empty($eachValue)) {
            $messages[] = sprintf('Missing second part of "each" expression ("%s")', $this->value);
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
