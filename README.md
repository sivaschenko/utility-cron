# PHP Cron Library

[![Build Status](https://travis-ci.org/sivaschenko/utility-cron.svg?branch=master)](https://travis-ci.org/sivaschenko/utility-cron)
[![StyleCI](https://styleci.io/repos/73108022/shield?style=flat)](https://styleci.io/repos/73108022)

The PHP Cron Library can be used to get human readable cron expression description and detailed validation messages.
Any level of expression complexity is handled.

# Code example

Human readable cron expression description example:

    $expression = \Sivaschenko\Utility\Cron\ExpressionFactory::getExpression('5 4 8 * *');
    
    echo $expression->getVerbalString(); // "At 04:05, on 8th day of month."
    
Cron expression validation example:

    $expression = \Sivaschenko\Utility\Cron\ExpressionFactory::getExpression('60 * * * 2- *');
    
    if (!$expression->isValid()) {
        print_r($expression->getValidationMessages());
    }
    
    /*
    Array
    (
        [0] => Minute expression part value "60" is greater than max allowed "59"
        [1] => Missing second part of "range" expression ("2-")
    )
    */

# Functionality examples

Examples of expression translation to verbal format:

| Cron Expression | Description |
|---|---|
|  * * * * * |  At every minute. |
| @weekly  | On Monday at midnight  | 
| 5 4 8 * * | At 04:05, on 8th day of month. |
| * * * * FRIL * | At every minute, on last Friday of the month. |
| * * * 2/2 mon/3 | At every minute, every 2nd month starting from February, every 3rd day of week starting from Monday. |
| 30/5 2-6 ? jan,feb 2#4 2017 | Every 5th minute starting from 30th, every hour from 2nd through 6th, in January and February, every 4th Tuesday, in 2017 |

Examples of cron expression validation:

| Invalid Cron Expression | Validation Messages |
|---|---|
| fd * * * * * | Minute expression part value "fd" is not valid! Allowed values are integers from 0 to 59, keywords: "*", "?" |
| 60 * * * 2# * |  Minute expression part value "60" is greater than max allowed "59", Missing second part of "each" expression ("2#") |
| * * * * mon/tue * | Second part of expression "mon/tue" can only be integer! |
| * * 4L * * * | Day of month expression part value "4L" is not valid! Allowed values are integers from 1 to 31, keywords: "*", "?", "W", "L" Allowed suffixes: "W" |
| @invalid | Unknown shortcut expression "@invalid"! List of valid shortcut expressions: "@reboot", "@midnight", "@daily", "@yearly", "@annually", "@monthly", "@weekly", "@hourly". |

# Installation

    composer require sivaschenko/utility-cron
    
# Test Coverage

Verbal translation, validation and even exceptions are covered with integration tests.

See [\Sivaschenko\Utility\Cron\Test\ExpressionTest](//github.com/sivaschenko/utility-cron/blob/master/Sivaschenko/Utility/Cron/Test/ExpressionTest.php) for details.