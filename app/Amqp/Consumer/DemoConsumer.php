<?php

declare(strict_types=1);

namespace App\Amqp\Consumer;

use App\Helper\Log;
use Hyperf\Amqp\Result;
use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Message\ConsumerMessage;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * @Consumer(exchange="hyperf21111", routingKey="hyperf1", queue="hyperf1", name ="DemoConsumer", nums=1)
 */
class DemoConsumer extends ConsumerMessage
{
    public function consumeMessage($data, AMQPMessage $message): string
    {
        Log::get('log')->info('【消费1】保存用户信息消息【消费1】', $data);
        return Result::NACK;
    }
}
