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
namespace Luffy\AliHbaseThrift;

use Luffy\AliHbaseThrift\Service\AliHbaseThriftInterface;
use Luffy\AliHbaseThrift\Service\HbaseFactory;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                AliHbaseThriftInterface::class => HbaseFactory::class,
            ],
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                    'ignore_annotations' => [
                        'parem',
                        'generated',
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config of ali hbase client.',
                    'source' => __DIR__ . '/../publish/hbase.php',
                    'destination' => BASE_PATH . '/config/autoload/hbase.php',
                ],
            ],
        ];
    }
}
