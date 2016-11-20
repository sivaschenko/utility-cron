<?php
/**
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron;

use Sivaschenko\Utility\Cron\Expression\PartInterface;
use Sivaschenko\Utility\Cron\Expression\TypeInterface;

class Expression implements ExpressionInterface
{
    /**
     * @var string[]|TypeInterface[]
     */
    private $types = [
        Expression\Type\Range::class,
        Expression\Type\Increment::class,
        Expression\Type\Selection::class,
        Expression\Type\Each::class,
        Expression\Type\Regular::class,
    ];

    /**
     * @var string[]|PartInterface[]
     */
    private $parts = [
        Expression\Part\Minute::class,
        Expression\Part\Hour::class,
        Expression\Part\Day::class,
        Expression\Part\Month::class,
        Expression\Part\DayOfWeek::class,
        Expression\Part\Year::class,
    ];

    /**
     * @var PartInterface[]
     */
    private $expressionParts = [];

    /**
     * Expression constructor.
     *
     * @param string $value
     */
    public function __construct($value)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Expression should be of string type! %s given.',
                    gettype($value)
                )
            );
        }

        $parts = explode(' ', $value);
        $numberParts = count($parts);

        if ($numberParts > 6 || $numberParts < 5) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Expression has %s parts (separated by space). Expected 5 or 6 parts (year part is optional)',
                    $numberParts
                )
            );
        }

        foreach ($parts as $index => $part) {
            if ($index >= 6) {
                break;
            }
            $this->expressionParts[] = $this->createPart($part, $index);
        }
    }

    /**
     * @param string $part
     * @param int    $index
     *
     * @throws \Exception
     *
     * @return TypeInterface
     */
    private function createPart($part, $index)
    {
        foreach ($this->types as $typeClass) {
            if ($typeClass::isApplicable($part)) {
                return new $typeClass($part, $this->parts[$index]);
            }
        }

        $partClass = $this->parts[$index];

        throw new \Exception(
            sprintf(
                'Cannot identify type of "%s" part of expression - ""!',
                $partClass::NAME,
                $part
            )
        );
    }

    /**
     * @return string
     */
    public function getVerbalString()
    {
        if (is_numeric($this->expressionParts[0]->getValue()) && is_numeric($this->expressionParts[1]->getValue())) {
            $parts = $this->expressionParts;

            $minute = array_shift($parts);
            $hour = array_shift($parts);

            $stringParts = [];
            $format = 'At %02d:%02d';
            foreach ($parts as $part) {
                $verbalString = $part->getVerbalString();
                if (!empty($verbalString)) {
                    $stringParts[] = $verbalString;
                    $format .= ', %s';
                }
            }

            return sprintf(
                $format.'.',
                $hour->getValue(),
                $minute->getValue(),
                implode(', ', $stringParts)
            );
        }

        $stringParts = [];
        foreach ($this->expressionParts as $part) {
            $stringParts[] = $part->getVerbalString();
        }

        return ucfirst(implode(', ', array_filter($stringParts))).'.';
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        if (empty($this->getValidationMessages())) {
            return true;
        }

        return false;
    }

    /**
     * @return string[]
     */
    public function getValidationMessages()
    {
        $messages = [];
        foreach ($this->expressionParts as $part) {
            $messages = array_merge($messages, $part->getValidationMessages());
        }

        return $messages;
    }
}
