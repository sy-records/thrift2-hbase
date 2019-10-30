# thrift2-hbase
thrift2-hbase component for hyperf.

> 此 repo 为使用阿里云云数据库 Hbase 性能增强版，HBase 增强版使用的 Thrift 接口定义是 HBase 的 `thrift2`

## 在 Hyperf 中使用

### 配置文件

配置文件位于 `config/autoload/hbase.php`，如配置文件不存在可通过执行

```shell
php bin/hyperf.php vendor:publish sy-records/thrift2-hbase
```

命令创建默认配置，配置文件内容如下：

```php
<?php

declare(strict_types=1);

return [
	'default' => [
		'host' => env('ALIHBASE_HOST', 'localhost'),
		'port' => env('ALIHBASE_PORT', 9190),
		'key_id' => env('ALIHBASE_KEYID', 'root'),
		'signature' => env('ALIHBASE_SIGNATURE', 'root'),
	],
];
```

### 添加配置信息

在项目根目录`.env`文件中添加相关配置信息

```dotenv
ALIHBASE_HOST=localhost
ALIHBASE_PORT=9190
ALIHBASE_KEYID=root
ALIHBASE_SIGNATURE=root
```

参数说明：

|        配置        |  类型  |  默认值   |                             备注                             |
| :----------------: | :----: | :-------: | :----------------------------------------------------------: |
|   ALIHBASE_HOST    | string | localhost | 连接信息中的“非 JAVA 语言 Thrift2 访问地址”访问地址部分（注意专有网络地址和外网地址的区别） |
|   ALIHBASE_PORT    |  int   |   9190    |                         PHP 操作端口                         |
|   ALIHBASE_KEYID   | string |   root    |                            用户名                            |
| ALIHBASE_SIGNATURE | string |   root    |                             密码                             |
