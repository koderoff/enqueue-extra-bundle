<?php

namespace Koderoff\EnqueueExtraBundle\Kafka;

use Enqueue\RdKafka\RdKafkaConnectionFactory;
use Enqueue\RdKafka\RdKafkaContext;
use Interop\Queue\Context;

/**
 * Class ConnectionFactory
 */
class ConnectionFactory extends RdKafkaConnectionFactory
{
    /**
     * @var string|array
     */
    protected $config;

    /**
     * ConnectionFactory constructor.
     *
     * @param string $config
     */
    public function __construct($config = 'kafka:')
    {
        parent::__construct($config);

        $this->config = $config;
    }

    /**
     * @return Context
     */
    public function createContext(): Context
    {
        $context = new RdKafkaContext($this->config);
        $context->setSerializer(new RawSerializer());

        return $context;
    }
}
