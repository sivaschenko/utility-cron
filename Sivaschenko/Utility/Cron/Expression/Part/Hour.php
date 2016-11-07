<?php
/**
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Expression\Part;

class Hour extends AbstractPart
{
    const NAME = 'hour';
    const PREFIX = 'at';
    const SUFFIX = 'hour';

    const MIN_INT_VALUE = 0;
    const MAX_INT_VALUE = 23;

    /**
     * @return string
     */
    protected function _getVerbalString()
    {
        return $this->getValueWithOrdinalSuffix();
    }
}
