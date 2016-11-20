<?php
/**
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Expression\Type;

class Increment extends AbstractType
{
    const DELIMITER = '/';

    /**
     * @return string
     */
    public function getVerbalString()
    {
        return sprintf(
            'every %s %s%s',
            $this->getSecondPart()->getValueWithOrdinalSuffix(),
            $this->getFirstPart()->getName(),
            in_array($this->getFirstPart()->getValue(), ['*', '?'])
                ? ''
                : ' starting from '.$this->getFirstPart()->getVerbalString()
        );
    }

    /**
     * @return \string[]
     */
    public function getValidationMessages()
    {
        $messages = [];
        if (empty($this->getFirstPart()->getValue())) {
            $messages[] = sprintf('Missing first part of "increment" expression ("%s")', $this->value);
        } else {
            $messages = array_merge($messages, $this->getFirstPart()->getValidationMessages());
        }
        $eachValue = $this->getSecondPart()->getValue();
        if (empty($eachValue)) {
            $messages[] = sprintf('Missing second part of "increment" expression ("%s")', $this->value);
        } else {
            if (!$this->isInteger($eachValue)) {
                $messages[] = sprintf(
                    'Second part of expression "%s" can only be integer!',
                    $this->value
                );
            }
        }

        return $messages;
    }
}
