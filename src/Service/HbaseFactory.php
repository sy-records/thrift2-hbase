<?php

declare(strict_types=1);

namespace Luffy\AliHbaseThrift\Serivce;

use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

class HbaseFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class)->get('hbase.default');

        return make(AliHbaseThriftService::class, $config);
    }
}
