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
return [
    'default' => [
        'host' => env('ALIHBASE_HOST', 'localhost'),
        'port' => env('ALIHBASE_PORT', 9190),
        'key_id' => env('ALIHBASE_KEYID', 'root'),
        'signature' => env('ALIHBASE_SIGNATURE', 'root'),
    ],
];
