<?php
/**
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Expression\Type;

class Selection extends AbstractType
{
    const DELIMITER = ',';

    /**
     * @return string
     */
    public function getVerbalString()
    {
        $values = [];
        foreach ($this->parts as $part) {
            $values[] = $part->getVerbalString();
        }

        return $this->getVerboseList($values).$this->getFirstPart()->getSuffix();
    }

    /**
     * @param string $values
     *
     * @return string
     */
    private function getVerboseList($values)
    {
        $last = array_pop($values);

        return $this->getFirstPart()->getPrefix().implode(', ', $values).' and '.$last;
    }

    /**
     * @return array
     */
    public function getValidationMessages()
    {
        $messages = [];
        foreach ($this->parts as $part) {
            if ('' === $part->getValue()) {
                $messages[] = sprintf(
                    'Comma separated list "%s" contains empty items or unnecessary commas!',
                    $this->value
                );
            } else {
                if ('W' == $part->getValue()) {
                    $messages[] = sprintf(
                        'Unexpected value "W" (weekdays) in comma separated list ("%s")!',
                        $this->value
                    );
                }
                $messages = array_merge($messages, $part->getValidationMessages());
            }
        }

        return $messages;
    }
}
