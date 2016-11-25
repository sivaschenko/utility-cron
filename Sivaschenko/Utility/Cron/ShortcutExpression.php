<?php
/**
 * Crafted with ♥ for developers
 *
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron;

class ShortcutExpression implements ExpressionInterface
{
    /**
     * @var array
     */
    private $shortcuts = [
        '@reboot'   => 'On Reboot.',
        '@midnight' => 'At Midnight.',
        '@daily'    => 'At Midnight.',
        '@yearly'   => '1st January at midnight.',
        '@annually' => '1st January at midnight.',
        '@monthly'  => '1st each month at midnight.',
        '@weekly'   => 'On Monday at midnight.',
        '@hourly'   => 'Each Hour.',
    ];

    /**
     * @var string
     */
    protected $value;

    /**
     * ShortcutExpression constructor.
     *
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return string[]
     */
    public function getValidationMessages()
    {
        return $this->isValid() ? [] : [sprintf(
            'Unknown shortcut expression "%s"! List of valid shortcut expressions: "%s".',
            $this->value,
            implode('", "', array_keys($this->shortcuts))
        )];
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return isset($this->shortcuts[$this->value]);
    }

    /**
     * @return string
     */
    public function getVerbalString()
    {
        return isset($this->shortcuts[$this->value]) ? $this->shortcuts[$this->value] : '';
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
