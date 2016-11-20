<?php
/**
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Expression;

interface TypeInterface
{
    /**
     * Delimiter for breaking expression into parts.
     */
    const DELIMITER = '';

    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isApplicable($value);

    /**
     * @return string
     */
    public function getVerbalString();

    /**
     * @return string
     */
    public function getValue();

    /**
     * @return bool
     */
    public function isValid();

    /**
     * @return string[]
     */
    public function getValidationMessages();
}
