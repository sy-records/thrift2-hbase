<?php

declare(strict_types=1);
/**
 * This file is part of thrift2-hbase.
 *
 * @link     https://github.com/sy-records/thrift2-hbase
 * @document https://github.com/sy-records/thrift2-hbase
 * @contact  52o@qq52o.cn
 * @license  https://github.com/sy-records/thrift2-hbase/blob/master/LICENSE
 */
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
