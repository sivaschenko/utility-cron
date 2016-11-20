<?php
/**
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron;

class ExpressionFactory
{
    /**
     * Performs basic value validation and constructs appropriate expression instance.
     *
     * @param string $value
     *
     * @return ExpressionInterface
     */
    public static function getExpression($value)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Expression should be of string type! %s given.',
                    gettype($value)
                )
            );
        }

        if (0 === strpos($value, '@')) {
            return new ShortcutExpression($value);
        }

        return new Expression($value);
    }
}
