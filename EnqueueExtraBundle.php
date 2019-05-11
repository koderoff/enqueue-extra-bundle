<?php

namespace Koderoff\EnqueueExtraBundle;

use Koderoff\EnqueueExtraBundle\DependencyInjection\Compiler\RegisterSubscriberPass;
use Koderoff\EnqueueExtraBundle\Subscriber\CommandSubscriberInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class EnqueueExtraBundle
 */
class EnqueueExtraBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(CommandSubscriberInterface::class)
            ->addTag('koderoff.exb.command_subscriber')
        ;

        $container->addCompilerPass(new RegisterSubscriberPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 100);
    }
}
