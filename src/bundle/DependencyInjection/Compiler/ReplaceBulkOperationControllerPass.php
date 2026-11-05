<?php

/**
 * @copyright Copyright (C) Vidar Langseid. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace VidarL\IbexaBulkOperationGuardBundle\DependencyInjection\Compiler;

use Ibexa\Bundle\AdminUi\Controller\BulkOperation\BulkOperationController as UpstreamBulkOperationController;
use Ibexa\Contracts\Rest\UriParser\UriParserInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use VidarL\IbexaBulkOperationGuardBundle\Controller\BulkOperationController;

final class ReplaceBulkOperationControllerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(UpstreamBulkOperationController::class)) {
            return;
        }

        // The route keeps pointing at the upstream service id, only the implementation behind it
        // changes. Arguments are set explicitly so the pass does not depend on running before
        // AutowirePass.
        $definition = $container->getDefinition(UpstreamBulkOperationController::class);
        $definition->setClass(BulkOperationController::class);
        $definition->setAutowired(false);
        $definition->setArguments([
            '$httpKernel' => new Reference('http_kernel'),
            '$restUriParser' => new Reference(UriParserInterface::class),
        ]);
    }
}
