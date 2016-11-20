<?php
/**
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Expression\Type;

class Regular extends AbstractType
{
    /**
     * @return string
     */
    public function getVerbalString()
    {
        $string = $this->getFirstPart()->getVerbalString();
        if ($this->getFirstPart()->getName() != 'minute' && in_array($string, ['every', 'any'])) {
            return '';
        }

        return $this->getFirstPart()->getPrefix().$string.$this->getFirstPart()->getSuffix();
    }
}
