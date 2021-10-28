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
namespace Luffy\AliHbaseThrift\Serivce;

interface AliHbaseThriftInterface
{
    public function getRow(string $tableName, string $rowKey): array;

    public function getTableNamesByNamespace(string $namespace): array;

    public function getColumn(string $tableName, string $rowKey, int $timestamp = 0, array $columns = []): array;

    public function getMultiple(string $tableName, array $params = []): array;

    public function getRowMultiple(string $tableName, array $rowKeys = [], array $columns = []): array;

    public function putValue(string $tableName, string $rowKey, string $family, array $qualifierValue = []): void;

    public function putMultiple(string $tableName, array $params = []): void;

    public function deleteByRowKey(string $tableName, string $rowKey = null): void;

    public function deleteSingle(string $tableName, string $rowKey = null, array $columns = []): void;
}
