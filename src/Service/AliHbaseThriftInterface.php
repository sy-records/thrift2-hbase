<?php
/**
 * AliHbaseThriftInterface
 *
 * @author    Luffy (lufei@swoole.com)
 * @date      2019/10/31
 * @copyright Swoole, Inc.
 * @package   sy-records/thrift2-hbase
 */

namespace Luffy\AliHbaseThrift\Serivce;

interface AliHbaseThriftInterface
{
    /**
     * @param  string $tableName
     * @param  string $rowKey
     * @return array
     */
    public function getRow(string $tableName, string $rowKey): array;

    /**
     * @param  string $namespace
     * @return array
     */
    public function getTableNamesByNamespace(string $namespace): array;

    /**
     * @param  string $tableName
     * @param  string $rowKey
     * @param  int    $timestamp
     * @param  array  $columns
     * @return array
     */
    public function getColumn(string $tableName, string $rowKey, int $timestamp = 0, array $columns = []): array;

	/**
	 * @param string $tableName
	 * @param array $params
	 * @return array
	 */
    public function getMultiple(string $tableName, array $params = []): array;

	/**
	 * @param string $tableName
	 * @param array $rowKeys
	 * @return array
	 */
    public function getRowMultiple(string $tableName, array $rowKeys = []): array;

    /**
     * @param string $tableName
     * @param string $rowKey
     * @param string $family
     * @param array  $qualifierValue
     */
    public function putValue(string $tableName, string $rowKey, string $family, array $qualifierValue = []): void;

	/**
	 * @param string $tableName
	 * @param array $params
	 */
	public function putMultiple(string $tableName, array $params = []): void;

	/**
	 * @param string $tableName
	 * @param string|null $rowKey
	 */
	public function deleteByRowKey(string $tableName, string $rowKey = null): void;

	public function deleteSingle(string $tableName, string $rowKey = null, array $columns = []): void;
}