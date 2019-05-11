<?php

namespace Koderoff\EnqueueExtraBundle\DependencyInjection\Compiler;

use Koderoff\EnqueueExtraBundle\Subscriber\CommandSubscriberInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class RegisterSubscriberPass
 */
class RegisterSubscriberPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        $services = $container->findTaggedServiceIds('koderoff.exb.command_subscriber');
        /** @var CommandSubscriberInterface $id */
        foreach ($services as $id => $tags) {
            $definition = $container->getDefinition($id);
            $definition->clearTags();
            $definition->addTag('enqueue.command_subscriber', ['client' => $id::getClient()]);
        }
    }
}
