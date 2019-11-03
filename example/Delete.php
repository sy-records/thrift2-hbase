<?php
/**
 * User: lufei
 * Date: 2019/11/1
 * Email: lufei@swoole.com.
 */

namespace Lufyy\HbaseExample;

require __DIR__ . "/../vendor/autoload.php";

use Luffy\AliHbaseThrift\Serivce\AliHbaseThriftService;
use Luffy\Thrift2Hbase\TDelete;

class Delete
{

	/**
	 * @var AliHbaseThriftService
	 */
	private $aliHbaseThriftService;

	/**
	 * @var \Luffy\Thrift2Hbase\THBaseServiceClient
	 */
	private $client;

	public function __construct($config)
	{
		$this->aliHbaseThriftService = new AliHbaseThriftService($config['host'], $config['port'], $config['key_id'], $config['signature']);
		$this->client = $this->aliHbaseThriftService->getClient();
	}

	public function run()
	{
		$namespace = "scanface"; // 相当于 mysql 的 库
		$table_name = "scanface:test";
		$family = "test";
		$row_key = "95523f245B2497157248551200014332";

//		$res = $this->aliHbaseThriftService->getRow($table_name, $row_key);
//		var_dump($res);
//		$this->client->deleteSingle($table_name, new TDelete(["row" => $row_key]));
//		$res = $this->aliHbaseThriftService->getRow($table_name, $row_key);
//		var_dump($res);

		$res = $this->aliHbaseThriftService->getRow($table_name, "95523f245B2497157248551200014333");
		var_dump($res);
		$this->aliHbaseThriftService->deleteByRowKey($table_name, "95523f245B2497157248551200014333");
		$res = $this->aliHbaseThriftService->getRow($table_name, "95523f245B2497157248551200014333");
		var_dump($res);

		$columnArray = [
			[
				"family" => $family,
				"qualifier" => "put_test_1",
			],
		];
		$this->aliHbaseThriftService->deleteSingle($table_name, $row_key, $columnArray);
		$res = $this->aliHbaseThriftService->getRow($table_name, $row_key);
		var_dump($res);

	}
}

$config = include "config.php";
$delete = new Delete($config);
$delete->run();