<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: tyt
 * Date: 2021/1/4
 * Time: 16:05
 */

namespace App\Helper;


use Hyperf\Utils\ApplicationContext;

class Log
{
    public static function get(string $name = 'app')
    {
        return ApplicationContext::getContainer()->get(\Hyperf\Logger\LoggerFactory::class)->get($name);
    }

}