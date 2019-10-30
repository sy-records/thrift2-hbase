<?php

declare(strict_types=1);

namespace Luffy\AliHbaseThrift;

use Luffy\AliHbaseThrift\Serivce\AliHbaseThriftInterface;
use Luffy\AliHbaseThrift\Serivce\HbaseFactory;

class ConfigProvider
{
	public function __invoke(): array
	{
		return [
			'dependencies' => [
				AliHbaseThriftInterface::class => HbaseFactory::class
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
						'generated'
					],
				]
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
