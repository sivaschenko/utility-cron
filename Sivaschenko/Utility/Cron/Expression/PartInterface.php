<?php
/**
 * Crafted with ♥ for developers.
 *
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Expression;

interface PartInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getValue();

    /**
     * @return string
     */
    public function getVerbalString();

    /**
     * @return string[]
     */
    public function getValidationMessages();

    /**
     * @return string
     */
    public function getPrefix();

    /**
     * @return string
     */
    public function getSuffix();
}
