<?php

/**
 * @copyright Copyright (C) Vidar Langseid. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace VidarL\IbexaBulkOperationGuardBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use VidarL\IbexaBulkOperationGuardBundle\DependencyInjection\Compiler\ReplaceBulkOperationControllerPass;

class VidarLIbexaBulkOperationGuardBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ReplaceBulkOperationControllerPass());
    }
}
