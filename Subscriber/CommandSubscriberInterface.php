<?php

namespace Koderoff\EnqueueExtraBundle\Subscriber;

use Enqueue\Client\CommandSubscriberInterface as BaseCommandSubscriberInterface;

/**
 * Interface CommandSubscriberInterface
 */
interface CommandSubscriberInterface extends BaseCommandSubscriberInterface
{
    /**
     * @return string
     */
    public static function getClient(): string;
}
