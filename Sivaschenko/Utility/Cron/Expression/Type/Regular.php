<?php
/**
 * Crafted with ♥ for developers
 *
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
        $part = $this->getFirstPart();

        if ($part->getName() != 'minute' && in_array($part->getValue(), ['*', '?'])) {
            return '';
        }

        return trim(sprintf('%s %s %s', $part->getPrefix(), $part->getVerbalString(), $part->getSuffix()));
    }
}
