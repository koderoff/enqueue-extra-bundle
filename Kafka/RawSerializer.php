<?php

namespace Koderoff\EnqueueExtraBundle\Kafka;

use Enqueue\RdKafka\RdKafkaMessage;
use Enqueue\RdKafka\Serializer;

/**
 * Class RawSerializer
 */
class RawSerializer implements Serializer
{
    /**
     * @param RdKafkaMessage $message
     *
     * @return string
     */
    public function toString(RdKafkaMessage $message): string
    {
        return $message->getBody();
    }

    /**
     * @param string $string
     *
     * @return RdKafkaMessage
     */
    public function toMessage(string $string): RdKafkaMessage
    {
        return new RdKafkaMessage($string);
    }
}
