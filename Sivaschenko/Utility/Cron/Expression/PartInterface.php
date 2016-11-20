<?php
/**
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Expression;

interface PartInterface
{
    /**
     * Name of expression part (minute, hour, day of month, month, day of week, year).
     */
    const NAME = 'part';

    /**
     * Prefix applicable to expression part (at, on, in, etc.).
     */
    const PREFIX = 'at';

    /**
     * Suffix applicable to expression part (minute, hour, etc.).
     */
    const SUFFIX = '';

    /**
     * Maximum integer value applicable for expression part.
     */
    const MAX_INT_VALUE = 7;

    /**
     * Minimum integer value applicable for expression part.
     */
    const MIN_INT_VALUE = 0;

    /**
     * @return string
     */
    public function getVerbalString();

    /**
     * @return string
     */
    public function getValue();

    /**
     * @return string[]
     */
    public function getValidationMessages();

    /**
     * @return string
     */
    public function getValueWithOrdinalSuffix();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getPrefix();

    /**
     * @return string
     */
    public function getSuffix();
}
