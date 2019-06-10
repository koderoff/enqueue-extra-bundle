<?php

namespace Koderoff\EnqueueExtraBundle\Extension;

use Enqueue\Consumption\Context\End;
use Enqueue\Consumption\Context\MessageResult;
use Enqueue\Consumption\Context\PostConsume;
use Enqueue\Consumption\Context\Start;
use Enqueue\Consumption\EndExtensionInterface;
use Enqueue\Consumption\MessageResultExtensionInterface;
use Enqueue\Consumption\PostConsumeExtensionInterface;
use Enqueue\Consumption\StartExtensionInterface;
use Enqueue\Consumption\Result;
use Interop\Queue\Consumer;
use Interop\Queue\Message;

/**
 * Class CommitRateReducer
 */
class CommitRateReducer implements MessageResultExtensionInterface, EndExtensionInterface, PostConsumeExtensionInterface, StartExtensionInterface
{
    /**
     * @var int
     */
    private $stamp;

    /**
     * @var Message
     */
    private $uncommited;

    /**
     * @var Consumer
     */
    private $consumer;

    /**
     * @param Start $context
     */
    public function onStart(Start $context): void
    {
        $this->stamp = time();
    }

    /**
     * @param MessageResult $context
     */
    public function onResult(MessageResult $context): void
    {
        if (null === $this->consumer) {
            $this->consumer = $context->getConsumer();
        }

        if ($context->getResult() !== Result::ACK) {
            return;
        }

        if ($this->stamp === time()) {
            $context->changeResult(Result::ALREADY_ACKNOWLEDGED);
            $this->uncommited = $context->getMessage();

            return;
        }

        $this->stamp = time();
        $this->uncommited = null;
    }

    /**
     * @param PostConsume $context
     */
    public function onPostConsume(PostConsume $context): void
    {
        if (null === $this->uncommited) {
            return;
        }

        $this->commit($this->uncommited);
    }

    /**
     * @param End $context
     */
    public function onEnd(End $context): void
    {
        if (null === $this->uncommited) {
            return;
        }

        $this->commit($this->uncommited);
    }

    /**
     * @param Message $message
     */
    private function commit(Message $message): void
    {
        $this->consumer->acknowledge($message);
        $this->uncommited = null;
    }
}
