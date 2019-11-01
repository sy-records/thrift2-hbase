<?php
/**
 * User: lufei
 * Date: 2019/11/1
 * Email: lufei@swoole.com.
 */

namespace Lufyy\HbaseExample;

require __DIR__ . "/../vendor/autoload.php";

use Luffy\AliHbaseThrift\Serivce\AliHbaseThriftService;

class Put
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
		$row_key = "95523f245B2497157248551200014331";

		$putValueArr = [
			"async_pay_status" => "2",
			"pay_way" => "3"
		];
		$this->aliHbaseThriftService->putValue($table_name, $row_key, $family, $putValueArr);

		$get_row = $this->aliHbaseThriftService->getRow($table_name, $row_key);
		var_dump($get_row);
	}
}
$config = include "config.php";
$get = new Put($config);
$get->run();