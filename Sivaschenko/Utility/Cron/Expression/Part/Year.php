<?php
/**
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Expression\Part;

class Year extends AbstractPart
{
    const NAME = 'year';
    const PREFIX = 'in';

    const MIN_INT_VALUE = 1970;
    const MAX_INT_VALUE = 2099;
}
