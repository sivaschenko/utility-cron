<?php
/**
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron;

/**
 * @api
 */
interface ExpressionInterface
{
    /**
     * Get human readable description of cron expression.
     *
     * @return string
     */
    public function getVerbalString();

    /**
     * Is expression valid.
     *
     * @return bool
     */
    public function isValid();

    /**
     * If expression is not valid will return array of messages with details
     * If expression is valid will return empty array.
     *
     * @return string[]
     */
    public function getValidationMessages();
}
