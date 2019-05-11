<?php

namespace Koderoff\EnqueueExtraBundle\Extension;

use Enqueue\Consumption\Context\End;
use Enqueue\Consumption\Context\MessageResult;
use Enqueue\Consumption\Context\PostConsume;
use Enqueue\Consumption\EndExtensionInterface;
use Enqueue\Consumption\MessageResultExtensionInterface;
use Enqueue\Consumption\PostConsumeExtensionInterface;
use Enqueue\Consumption\Result;
use Enqueue\RdKafka\RdKafkaMessage;
use Interop\Queue\Context;
use Interop\Queue\Message;

/**
 * Class CommitRateReducer
 */
class CommitRateReducer implements MessageResultExtensionInterface, EndExtensionInterface, PostConsumeExtensionInterface
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
     * @param MessageResult $context
     */
    public function onResult(MessageResult $context): void
    {
        if ($context->getResult() !== Result::ACK) {
            return;
        }

        if (null === $this->stamp) {
            $this->stamp = time();
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

        $this->commit($context->getContext(), $this->uncommited);
    }

    /**
     * @param End $context
     */
    public function onEnd(End $context): void
    {
        if (null === $this->uncommited) {
            return;
        }

        $this->commit($context->getContext(), $this->uncommited);
    }

    /**
     * @param Context        $context
     * @param RdKafkaMessage $message
     */
    private function commit(Context $context, Message $message): void
    {
        // TODO: offset should be committed here

        $this->uncommited = null;
    }
}
