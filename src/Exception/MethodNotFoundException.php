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
namespace Luffy\AliHbaseThrift\Exception;

class MethodNotFoundException extends \Exception
{
    public function __construct($message = '', $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
