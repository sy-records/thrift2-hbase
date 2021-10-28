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
namespace Lufyy\HbaseExample;

require __DIR__ . '/../vendor/autoload.php';

use Luffy\AliHbaseThrift\Service\AliHbaseThriftService;

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
        $namespace = 'scanface'; // 相当于 mysql 的 库
        $table_name = 'scanface:test';
        $family = 'test';
        $row_key = '95523f245B2497157248551200014331';

        $putValueArr = [
            'async_pay_status' => '2',
            'pay_way' => '3',
        ];
        $this->aliHbaseThriftService->putValue($table_name, $row_key, $family, $putValueArr);

        $get_row = $this->aliHbaseThriftService->getRow($table_name, $row_key);
        var_dump($get_row);

        $puts_data = [
            [
                'row' => '95523f245B2497157248551200014332',
                'family' => $family,
                'columns' => [
                    'put_test_1' => '111',
                    'put_test_2' => '222',
                ],
            ],
            [
                'row' => '95523f245B2497157248551200014333',
                'family' => $family,
                'columns' => [
                    'puts_test_1' => '111',
                    'puts_test_2' => '222',
                ],
            ],
        ];
        $this->aliHbaseThriftService->putMultiple($table_name, $puts_data);

        // 验证
        $gets_data = [
            [
                'row' => '95523f245B2497157248551200014332',
            ],
            [
                'row' => '95523f245B2497157248551200014333',
            ],
        ];
        $gets = $this->aliHbaseThriftService->getMultiple($table_name, $gets_data);
        var_dump($gets);
    }
}
$config = include 'config.php';
$get = new Put($config);
$get->run();
