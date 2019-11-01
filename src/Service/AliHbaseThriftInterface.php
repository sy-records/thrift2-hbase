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
	 * @param string $tableName
	 * @param string $rowKey
	 * @return array
	 */
    public function getRow(string $tableName, string $rowKey): array;

	/**
	 * @param string $namespace
	 * @return array
	 */
    public function getTableNamesByNamespace(string $namespace): array;

	/**
	 * @param string $tableName
	 * @param string $rowKey
	 * @param int $timestamp
	 * @param array $columns
	 * @return array
	 */
    public function getColumn(string $tableName, string $rowKey, int $timestamp = 0, array $columns = []): array;

	public function getMultiple(string $tableName, array $columns = []): array;

	/**
	 * @param string $tableName
	 * @param string $rowKey
	 * @param string $family
	 * @param array $qualifierValue
	 */
	public function putValue(string $tableName, string $rowKey, string $family, array $qualifierValue = []): void;
}