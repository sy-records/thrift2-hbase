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
namespace Luffy\AliHbaseThrift;

use Luffy\AliHbaseThrift\Serivce\AliHbaseThriftInterface;
use Luffy\AliHbaseThrift\Serivce\HbaseFactory;

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
