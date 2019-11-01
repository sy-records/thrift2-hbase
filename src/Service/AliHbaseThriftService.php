<?php
/**
 * User: 周华
 * Date: 2019-10-28
 * Time: 10:35
 */

namespace Luffy\AliHbaseThrift\Serivce;


use Luffy\AliHbaseThrift\Exception\MethodNotFoundException;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TBufferedTransport;
use Thrift\Transport\THttpClient;
use Luffy\Thrift2Hbase\THBaseServiceClient;
use Luffy\Thrift2Hbase\TGet;
use Luffy\Thrift2Hbase\TPut;

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
        $headers = array('ACCESSKEYID' => $key_id, 'ACCESSSIGNATURE' => $signature);

        $socket = new THttpClient($host, $port);
        $socket->addHeaders($headers);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $this->client = new THBaseServiceClient($protocol);
    }

    /**
     * @return THBaseServiceClient
     */
    public function getClient()
    {
        return $this->client;
    }

    public function getRow($tableName, $rowKey): array
    {
        $get = new TGet();
        $get->row = $rowKey;

        $arr = $this->client->get($tableName, $get);
        $data = [];
        $results = $arr->columnValues;
        foreach ($results as $result) {
            $qualifier = (string)$result->qualifier;
            $value = $result->value;
            $data[$qualifier] = $value;
        }
        return $data;
    }

    public function getTableNamesByNamespace($namespace): array
    {
        $arr = $this->client->getTableNamesByNamespace($namespace);
        $tables = [];
        foreach ($arr as $item) {
            array_push($tables, (string)$item->qualifier);
        }
        return $tables;
    }

    public function getColumn($tableName, $rowKey, $timestamp = 0, $columns = []): array
    {
        $get = new TGet();
        $get->row = $rowKey;

        if(!empty($timestamp)) {
        	$get->timestamp = $timestamp;
		}

		$tcolumnArr = [];
        foreach ($columns as $column) {
			$family = $column['family'] ?? null;
			$qualifier = $column['qualifier'] ?? null;
			$timestamp_ = $column['timestamp'] ?? null;
			$tcolumn_obj = new \Luffy\Thrift2Hbase\TColumn();
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
            $qualifier = (string)$result->qualifier;
            $value = $result->value;
            $data[$qualifier] = $value;
        }
        return $data;
    }

	public function putValue(string $tableName, string $rowKey, string $family, array $qualifierValue = []): void
	{
		$put = new TPut();
		$put->row = $rowKey;

		$tcolumnArr = [];
		foreach ($qualifierValue as $key => $value) {
			$tcolumn_obj = new \Luffy\Thrift2Hbase\TColumnValue();
			$tcolumn_obj->family = $family;
			$tcolumn_obj->qualifier = $key;
			$tcolumn_obj->value = $value;
			array_push($tcolumnArr, $tcolumn_obj);
		}
		$put->columnValues = $tcolumnArr;

		$this->client->put($tableName, $put);
    }

	public function getMultiple(string $tableName, array $columns = []) : array
	{

	}

    /**
     * @param  $name
     * @param  $arguments
     * @return mixed
     * @throws MethodNotFoundException
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->client, $name)) {
            return $this->client->$name(...$arguments);
        }else{
            throw new MethodNotFoundException("method {$name} not found in ali hbase");
        }
    }
}