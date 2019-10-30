<?php
/**
 * User: lufei
 * Date: 2019/10/30
 * Email: lufei@swoole.com.
 */

declare(strict_types=1);

return [
	'default' => [
		'host' => env('ALIHBASE_HOST', 'localhost'),
		'port' => env('ALIHBASE_PORT', 9190),
		'key_id' => env('ALIHBASE_KEYID', 'root'),
		'signature' => env('ALIHBASE_SIGNATURE', 'root'),
	],
];