<?php
/**
 * User: lufei
 * Date: 2019/11/1
 * Email: lufei@swoole.com.
 */

namespace Lufyy\HbaseExample;

require __DIR__ . "/../vendor/autoload.php";

use Luffy\AliHbaseThrift\Serivce\AliHbaseThriftService;

class Get
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

        // 通过命名空间查找表名
        $tables = $this->aliHbaseThriftService->getTableNamesByNamespace($namespace);
        var_dump($tables);

        // 通过row_key获取单行
        $get_row = $this->aliHbaseThriftService->getRow($table_name, $row_key);
        var_dump($get_row);

        // 查询某个字段
        $columnArray = [
			[
				"family" => $family,
				"qualifier" => "async_pay_status",
			],
        ];
        $data = $this->aliHbaseThriftService->getColumn($table_name, $row_key, 0, $columnArray);
        var_dump($data);

        $gets_data = [
			[
				"row" => $row_key,
				"columns" => [
					[
						"family" => $family,
						"qualifier" => "async_pay_status",
					],
				]
			],
			[
				"row" => "95523f245B2497157248551200014330"
			],
		];
        $gets = $this->aliHbaseThriftService->getMultiple($table_name, $gets_data);
        var_dump($gets);

		$rows = ['955235245B2497157248538900037777','95523f245B2497157248551200014976'];
		$columns = [
			'family' => "oi",
			'qualifier' => [
				'async_pay_status',
				'create_time'
			]
		];
        $get_rows = $this->aliHbaseThriftService->getRowMultiple("scanface:order", $rows, $columns);
        var_dump($get_rows);
    }
}
$config = include "config.php";
$get = new Get($config);
$get->run();