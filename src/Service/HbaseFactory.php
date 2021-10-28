<?php

declare(strict_types=1);
/**
 * This file is part of thrift2-hbase.
 *
 * @link     https://github.com/sy-records/thrift2-hbase
 * @contact  Lu Fei <52o@qq52o.cn>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */
namespace Luffy\AliHbaseThrift\Service;

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
