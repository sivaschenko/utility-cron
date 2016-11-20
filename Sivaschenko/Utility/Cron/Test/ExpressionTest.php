<?php
/**
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Test;

use Sivaschenko\Utility\Cron\ExpressionFactory;

class ExpressionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider expressionProvider
     *
     * @param $expression
     * @param $expectedString
     */
    public function testGetVerbalString($expression, $expectedString)
    {
        $expression = ExpressionFactory::getExpression($expression);

        $this->assertEmpty(
            $expression->getValidationMessages(),
            var_export($expression->getValidationMessages(), true)
        );
        $this->assertTrue($expression->isValid());
        $this->assertEquals($expectedString, $expression->getVerbalString());
    }

    /**
     * @dataProvider invalidExpressionProvider
     *
     * @param string $expression
     * @param array  $messages
     */
    public function testGetValidationMessages($expression, array $messages)
    {
        $instance = ExpressionFactory::getExpression($expression);

        $this->assertEquals(
            $messages,
            $instance->getValidationMessages(),
            $expression.' '.var_export($instance->getValidationMessages(), true)
        );

        $this->assertFalse($instance->isValid());
    }

    /**
     * @dataProvider exceptionProvider
     *
     * @param string $expression
     * @param string $exceptionClass
     * @param string $exceptionMessage
     */
    public function testException($expression, $exceptionClass, $exceptionMessage)
    {
        $this->setExpectedException($exceptionClass, $exceptionMessage);
        ExpressionFactory::getExpression($expression);
    }

    /**
     * Data provider of valid cron expressions.
     *
     * @return array
     */
    public function expressionProvider()
    {
        return [
            ['* * * * * *', 'At every minute.'],
            ['? ? ? ? ? ?', 'At any minute.'],
            ['* * * * *', 'At every minute.'],
            ['1 * * * * *', 'At 1st minute.'],

            ['1-3 * * * * *', 'Every minute from 1st through 3rd.'],
            ['4,5,6 * * * * *', 'At 4th, 5th and 6th minute.'],
            ['2/4 * * * * *', 'Every 4th minute starting from 2nd.'],
            ['*/2 * * * * *', 'Every 2nd minute.'],

            ['* 1 * * * *', 'At every minute, at 1st hour.'],
            ['* 1-3 * * * *', 'At every minute, every hour from 1st through 3rd.'],
            ['* 4,5,6 * * * *', 'At every minute, at 4th, 5th and 6th hour.'],
            ['* 2/4 * * * *', 'At every minute, every 4th hour starting from 2nd.'],
            ['* 2/5 * * * *', 'At every minute, every 5th hour starting from 2nd.'],
            ['* */2 * * * *', 'At every minute, every 2nd hour.'],

            ['* * 1 * * *', 'At every minute, on 1st day of month.'],
            ['* * 1-3 * * *', 'At every minute, every day of month from 1st through 3rd.'],
            ['* * 4,5,6 * * *', 'At every minute, on 4th, 5th and 6th day of month.'],
            ['* * 2/4 * * *', 'At every minute, every 4th day of month starting from 2nd.'],
            ['* * 2/5 * * *', 'At every minute, every 5th day of month starting from 2nd.'],
            ['* * */2 * * *', 'At every minute, every 2nd day of month.'],

            ['* * * 1 * *', 'At every minute, in January.'],
            ['* * * 1-3 * *', 'At every minute, every month from January through March.'],
            ['* * * 4,5,6 * *', 'At every minute, in April, May and June.'],
            ['* * * 2/4 * *', 'At every minute, every 4th month starting from February.'],
            ['* * * 2/5 * *', 'At every minute, every 5th month starting from February.'],
            ['* * * */2 * *', 'At every minute, every 2nd month.'],

            ['* * * * 1 *', 'At every minute, on Monday.'],
            ['* * * * 1-3 *', 'At every minute, every day of week from Monday through Wednesday.'],
            ['* * * * 4,5,6 *', 'At every minute, on Thursday, Friday and Saturday.'],
            ['* * * * 2/4 *', 'At every minute, every 4th day of week starting from Tuesday.'],
            ['5 4 * * 2/4 *', 'At 04:05, every 4th day of week starting from Tuesday.'],
            ['* * * * 2/5 *', 'At every minute, every 5th day of week starting from Tuesday.'],
            ['* * * * */2 *', 'At every minute, every 2nd day of week.'],

            ['* * * * * 2022', 'At every minute, in 2022.'],
            ['* * * * * 2020-2022', 'At every minute, every year from 2020 through 2022.'],
            ['* * * * * 2021,2022,2023', 'At every minute, in 2021, 2022 and 2023.'],
            ['* * * * * 2018/4', 'At every minute, every 4th year starting from 2018.'],
            ['* * * * * */2', 'At every minute, every 2nd year.'],

            ['@reboot', 'On Reboot.'],
            ['@midnight', 'At Midnight.'],
            ['@daily', 'At Midnight.'],
            ['@yearly', '1st January at midnight.'],
            ['@annually', '1st January at midnight.'],
            ['@monthly', '1st each month at midnight.'],
            ['@weekly', 'On Monday at midnight.'],
            ['@hourly', 'Each Hour.'],

            ['* * * * L *', 'At every minute, on Sunday.'],
            ['* * * * 5L *', 'At every minute, on last Friday of the month.'],
            ['* * * * FRIL *', 'At every minute, on last Friday of the month.'],
            ['* * L * * *', 'At every minute, on last day of month.'],
            ['* * 15W * * *', 'At every minute, on closest weekday to 15th day of month.'],
            ['* * W * * *', 'At every minute, on weekdays.'],
            ['* * * * 3#2 *', 'At every minute, every 2nd Wednesday.'],
            ['* * * * mon#3', 'At every minute, every 3rd Monday.'],

            ['* * * 1,2,3,4,5,6,7,8,9,10,11,12 * *', 'At every minute, in January, February, March, April, May, June, July, August, September, October, November and December.'],
            ['* * * jan,feb,mar,apr,may,jun,jul,aug,sep,oct,nov,dec * *', 'At every minute, in January, February, March, April, May, June, July, August, September, October, November and December.'],
            ['* * * JAN,FEB,MAR,APR,MAY,JUN,JUL,AUG,SEP,OCT,NOV,DEC * *', 'At every minute, in January, February, March, April, May, June, July, August, September, October, November and December.'],
            ['* * * * 0,1,2,3,4,5,6,7 *', 'At every minute, on Sunday, Monday, Tuesday, Wednesday, Thursday, Friday, Saturday and Sunday.'],
            ['* * * * mon,tue,wed,thu,fri,sat,sun *', 'At every minute, on Monday, Tuesday, Wednesday, Thursday, Friday, Saturday and Sunday.'],
            ['* * * * MON,TUE,WED,THU,FRI,SAT,SUN *', 'At every minute, on Monday, Tuesday, Wednesday, Thursday, Friday, Saturday and Sunday.'],

            ['* * * feb-apr tue-thu *', 'At every minute, every month from February through April, every day of week from Tuesday through Thursday.'],
            ['* * * feb/2 mon/3', 'At every minute, every 2nd month starting from February, every 3rd day of week starting from Monday.'],

            ['5 4 * * *', 'At 04:05.'],
            ['5 4 8 * *', 'At 04:05, on 8th day of month.'],
            ['5 4 1 * *', 'At 04:05, on 1st day of month.'],
            ['* * * * *', 'At every minute.'],
            ['* * * * 1', 'At every minute, on Monday.'],
            ['* * * 1 *', 'At every minute, in January.'],
            ['1,2,3 1-2 5/3 */3 2', 'At 1st, 2nd and 3rd minute, every hour from 1st through 2nd, every 3rd day of month starting from 5th, every 3rd month, on Tuesday.'],
            ['* * * * 2-6', 'At every minute, every day of week from Tuesday through Saturday.'],
            ['30/5 2-6 ? jan,feb 2#4 2017', 'Every 5th minute starting from 30th, every hour from 2nd through 6th, in January and February, every 4th Tuesday, in 2017.'],
        ];
    }

    /**
     * Data provider of invalid cron expressions.
     *
     * @return array
     */
    public function invalidExpressionProvider()
    {
        return [
            ['-1 * * * * *', ['Missing first part of "range" expression ("-1")']],
            ['* -1 * * * *', ['Missing first part of "range" expression ("-1")']],
            ['* * 0 * * *', ['Day of month expression part value "0" is less than min allowed "1"']],
            ['* * * 0 * *', ['Month expression part value "0" is less than min allowed "1"']],
            ['* * * * -1 *', ['Missing first part of "range" expression ("-1")']],
            ['* * * * * 1969', ['Year expression part value "1969" is less than min allowed "1970"']],

            ['60 * * * * *', ['Minute expression part value "60" is greater than max allowed "59"']],
            ['fd * * * * *', ['Minute expression part value "fd" is not valid! Allowed values are integers from 0 to 59, keywords: "*", "?"']],
            ['* 24 * * * *', ['Hour expression part value "24" is greater than max allowed "23"']],
            ['* * 32 * * *', ['Day of month expression part value "32" is greater than max allowed "31"']],
            ['* * * 13 * *', ['Month expression part value "13" is greater than max allowed "12"']],
            ['* * * * 8 *', ['Day of week expression part value "8" is greater than max allowed "7"']],
            ['* * * * * 2100', ['Year expression part value "2100" is greater than max allowed "2099"']],

            ['* * * * 2#6 *', ['Second part of expression "2#6" cannot be greater than 5 (maximum number of weeks in a month)!']],
            ['* * * * mon/tue *', ['Second part of expression "mon/tue" can only be integer!']],

            ['@invalid', ['Unknown shortcut expression "@invalid"! List of valid shortcut expressions: "@reboot", "@midnight", "@daily", "@yearly", "@annually", "@monthly", "@weekly", "@hourly".']],

            ['* * 4L * * *', ['Day of month expression part value "4L" is not valid! Allowed values are integers from 1 to 31, keywords: "*", "?", "W", "L" Allowed suffixes: "W"']],
            ['* * * * # *', ['Missing first part of "each" expression ("#")', 'Missing second part of "each" expression ("#")']],
            ['* * * * #6 *', ['Missing first part of "each" expression ("#6")', 'Second part of expression "#6" cannot be greater than 5 (maximum number of weeks in a month)!']],
            ['* * * * 2# *', ['Missing second part of "each" expression ("2#")']],
            ['1/ * * * * *', ['Missing second part of "increment" expression ("1/")']],
            ['/2 * * * * *', ['Missing first part of "increment" expression ("/2")']],
            ['2, * * * * *', ['Comma separated list "2," contains empty items or unnecessary commas!']],
            [',2 * * * * *', ['Comma separated list ",2" contains empty items or unnecessary commas!']],
            ['2- * * * * *', ['Missing second part of "range" expression ("2-")']],
        ];
    }

    /**
     * Data provider of Exception::__construct() arguments that result into \InvalidArgumentException.
     *
     * @return array
     */
    public function exceptionProvider()
    {
        return [
            [new \stdClass(), 'InvalidArgumentException', 'Expression should be of string type! object given.'],
            [[], 'InvalidArgumentException', 'Expression should be of string type! array given.'],
            ['* * * *', 'InvalidArgumentException', 'Expression has 4 parts (separated by space). Expected 5 or 6 parts (year part is optional)'],
            ['* * * * * * *', 'InvalidArgumentException', 'Expression has 7 parts (separated by space). Expected 5 or 6 parts (year part is optional)'],
            ['invalid', 'InvalidArgumentException', 'Expression has 1 parts (separated by space). Expected 5 or 6 parts (year part is optional)'],
        ];
    }
}
