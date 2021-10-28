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

use Luffy\AliHbaseThrift\Exception\MethodNotFoundException;
use Luffy\Thrift2Hbase\TColumn;
use Luffy\Thrift2Hbase\TColumnValue;
use Luffy\Thrift2Hbase\TDelete;
use Luffy\Thrift2Hbase\TGet;
use Luffy\Thrift2Hbase\THBaseServiceClient;
use Luffy\Thrift2Hbase\TPut;
use Thrift\Protocol\TBinaryProtocolAccelerated;
use Thrift\Transport\TBufferedTransport;
use Thrift\Transport\THttpClient;

class AliHbaseThriftService implements AliHbaseThriftInterface
{
    /**
     * @var THBaseServiceClient
     */
    private $client;

    /**
     * AliHbaseThriftService constructor.
     *
     * @param $host
     * @param $port
     * @param $key_id
     * @param $signature
     */
    public function __construct($host, $port, $key_id, $signature)
    {
        $headers = ['ACCESSKEYID' => $key_id, 'ACCESSSIGNATURE' => $signature];

        $socket = new THttpClient($host, $port);
        $socket->addHeaders($headers);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocolAccelerated($transport);
        $this->client = new THBaseServiceClient($protocol);
    }

    /**
     * @param $name
     * @param $arguments
     * @throws MethodNotFoundException
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->client, $name)) {
            return $this->client->{$name}(...$arguments);
        }
        throw new MethodNotFoundException("method {$name} not found in ali hbase");
    }

    /**
     * @return THBaseServiceClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param string $tableName
     * @param string $rowKey
     * @throws \Luffy\Thrift2Hbase\TIOError
     */
    public function getRow($tableName, $rowKey): array
    {
        $get = new TGet();
        $get->row = $rowKey;

        $arr = $this->client->get($tableName, $get);
        $data = [];
        $results = $arr->columnValues;
        foreach ($results as $result) {
            $qualifier = (string) $result->qualifier;
            $value = $result->value;
            $data[$qualifier] = $value;
        }
        return $data;
    }

    /**
     * @param string $namespace
     * @throws \Luffy\Thrift2Hbase\TIOError
     */
    public function getTableNamesByNamespace($namespace): array
    {
        $arr = $this->client->getTableNamesByNamespace($namespace);
        $tables = [];
        foreach ($arr as $item) {
            array_push($tables, (string) $item->qualifier);
        }
        return $tables;
    }

    /**
     * @param string $tableName
     * @param string $rowKey
     * @param int $timestamp
     * @param array $columns
     * @throws \Luffy\Thrift2Hbase\TIOError
     */
    public function getColumn($tableName, $rowKey, $timestamp = 0, $columns = []): array
    {
        $get = new TGet();
        $get->row = $rowKey;

        if (! empty($timestamp)) {
            $get->timestamp = $timestamp;
        }

        $tcolumnArr = [];
        foreach ($columns as $column) {
            $family = $column['family'] ?? null;
            $qualifier = $column['qualifier'] ?? null;
            $timestamp_ = $column['timestamp'] ?? null;
            $tcolumn_obj = new TColumn();
            $tcolumn_obj->family = $family;
            $tcolumn_obj->qualifier = $qualifier;
            $tcolumn_obj->timestamp = $timestamp_;
            array_push($tcolumnArr, $tcolumn_obj);
        }

        $get->columns = $tcolumnArr;

        $arr = $this->client->get($tableName, $get);
        $data = [];
        $results = $arr->columnValues;
        foreach ($results as $result) {
            $qualifier = (string) $result->qualifier;
            $value = $result->value;
            $data[$qualifier] = $value;
        }
        return $data;
    }

    /**
     * @throws \Luffy\Thrift2Hbase\TIOError
     */
    public function getMultiple(string $tableName, array $params = []): array
    {
        $tgets = [];
        foreach ($params as $item) {
            $get = new TGet();
            $get->row = $item['row'] ?? null;
            $get->timestamp = $item['timestamp'] ?? null;

            $tcolumnArr = [];
            if (isset($item['columns']) and is_array($item['columns'])) {
                foreach ($item['columns'] as $column) {
                    $family = $column['family'] ?? null;
                    $qualifier = $column['qualifier'] ?? null;
                    $timestamp_ = $column['timestamp'] ?? null;
                    $tcolumn_obj = new TColumn();
                    $tcolumn_obj->family = $family;
                    $tcolumn_obj->qualifier = $qualifier;
                    $tcolumn_obj->timestamp = $timestamp_;
                    array_push($tcolumnArr, $tcolumn_obj);
                }
            }
            $get->columns = $tcolumnArr;

            array_push($tgets, $get);
        }
        $arr = $this->client->getMultiple($tableName, $tgets);

        $tgets_data = [];
        foreach ($arr as $item) {
            $data = [];
            $results = $item->columnValues;
            foreach ($results as $result) {
                $qualifier = (string) $result->qualifier;
                $value = $result->value;
                $data[$qualifier] = $value;
            }
            $tgets_data[$item->row] = $data;
        }
        return $tgets_data;
    }

    /**
     * @throws \Luffy\Thrift2Hbase\TIOError
     */
    public function getRowMultiple(string $tableName, array $rowKeys = [], array $columns = []): array
    {
        $tgets = [];
        foreach ($rowKeys as $row) {
            $get = new TGet();
            $get->row = $row ?? null;
            $tcolumnArr = [];
            if (! empty($columns) && is_array($columns)) {
                $family = $columns['family'] ?? null;
                foreach ($columns['qualifier'] as $item) {
                    $qualifier = $item ?? null;
                    $tcolumn_obj = new TColumn();
                    $tcolumn_obj->family = $family;
                    $tcolumn_obj->qualifier = $qualifier;
                    array_push($tcolumnArr, $tcolumn_obj);
                }
            }
            $get->columns = $tcolumnArr;
            array_push($tgets, $get);
        }
        $arr = $this->client->getMultiple($tableName, $tgets);
        $tgets_data = [];
        foreach ($arr as $item) {
            $data = [];
            $results = $item->columnValues;
            foreach ($results as $result) {
                $qualifier = (string) $result->qualifier;
                $value = $result->value;
                $data[$qualifier] = $value;
            }
            $tgets_data[$item->row] = $data;
        }
        return $tgets_data;
    }

    /**
     * @throws \Luffy\Thrift2Hbase\TIOError
     */
    public function putValue(string $tableName, string $rowKey, string $family, array $qualifierValue = []): void
    {
        $put = new TPut();
        $put->row = $rowKey;

        $tcolumnArr = [];
        foreach ($qualifierValue as $key => $value) {
            $tcolumn_obj = new TColumnValue();
            $tcolumn_obj->family = $family;
            $tcolumn_obj->qualifier = $key;
            $tcolumn_obj->value = $value;
            array_push($tcolumnArr, $tcolumn_obj);
        }
        $put->columnValues = $tcolumnArr;

        $this->client->put($tableName, $put);
    }

    /**
     * @throws \Luffy\Thrift2Hbase\TIOError
     */
    public function putMultiple(string $tableName, array $params = []): void
    {
        $tputs = [];
        foreach ($params as $item) {
            $put = new TPut();
            $put->row = $item['row'] ?? null;
            $put->timestamp = $item['timestamp'] ?? null;

            $family = $item['family'] ?? null;
            $tcolumnValues = [];
            if (isset($item['columns']) and is_array($item['columns'])) {
                foreach ($item['columns'] as $key => $column) {
                    $tcolumn_obj = new TColumnValue();
                    $tcolumn_obj->family = $family;
                    $tcolumn_obj->qualifier = $key;
                    $tcolumn_obj->value = $column;
                    array_push($tcolumnValues, $tcolumn_obj);
                }
            }
            $put->columnValues = $tcolumnValues;

            array_push($tputs, $put);
        }
        $this->client->putMultiple($tableName, $tputs);
    }

    /**
     * @throws \Luffy\Thrift2Hbase\TIOError
     */
    public function deleteByRowKey(string $tableName, string $rowKey = null): void
    {
        $delete = new TDelete();
        $delete->row = $rowKey;
        $this->client->deleteSingle($tableName, $delete);
    }

    public function deleteSingle(string $tableName, string $rowKey = null, array $columns = []): void
    {
        $delete = new TDelete();
        $delete->row = $rowKey;

        $tcolumnArr = [];
        foreach ($columns as $column) {
            $family = $column['family'] ?? null;
            $qualifier = $column['qualifier'] ?? null;
            $timestamp = $column['timestamp'] ?? null;
            $tcolumn_obj = new TColumn();
            $tcolumn_obj->family = $family;
            $tcolumn_obj->qualifier = $qualifier;
            $tcolumn_obj->timestamp = $timestamp;
            array_push($tcolumnArr, $tcolumn_obj);
        }

        $delete->columns = $tcolumnArr;

        $this->client->deleteSingle($tableName, $delete);
    }
}
