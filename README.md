# thrift2-hbase

thrift2-hbase component for Hyperf.

[![Latest Stable Version](http://poser.pugx.org/sy-records/thrift2-hbase/v)](https://packagist.org/packages/sy-records/thrift2-hbase)
[![Total Downloads](http://poser.pugx.org/sy-records/thrift2-hbase/downloads)](https://packagist.org/packages/sy-records/thrift2-hbase)
[![License](http://poser.pugx.org/sy-records/thrift2-hbase/license)](https://packagist.org/packages/sy-records/thrift2-hbase)
[![PHP Version Require](http://poser.pugx.org/sy-records/thrift2-hbase/require/php)](https://packagist.org/packages/sy-records/thrift2-hbase)

> 此 repo 为使用阿里云云数据库 Hbase 性能增强版，HBase 增强版使用的 Thrift 接口定义是 HBase 的 `thrift2`

## 安装

```shell
composer require sy-records/thrift2-hbase -vvv

#生成 classmap
composer dump-autoload -o
```

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

### 代码提示

在 PhpStorm 中直接操作是没有代码提示的，可添加`@var`使得编辑器增加代码提示，如下所示

```php
<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\Di\Annotation\Inject;
use Luffy\AliHbaseThrift\Service\AliHbaseThriftInterface;

class IndexController extends AbstractController
{
    /**
     * 使用注解时
     * @Inject()
     * @var AliHbaseThriftInterface
     */
    private $hbase;

    public function index()
    {
        /**
         * @var $client \Luffy\Thrift2Hbase\THBaseServiceClient
         */
        $client = $this->hbase->getClient();

        /**
         * @var $hbase \Luffy\AliHbaseThrift\Service\AliHbaseThriftService
         */
        $hbase = make(AliHbaseThriftInterface::class);

        /**
         * @var $client \Luffy\Thrift2Hbase\THBaseServiceClient
         */
        $client = $hbase->getClient();

        $res = $client->get("scanface:test", new \Luffy\Thrift2Hbase\TGet(["row" => "001"]));
        var_dump($res->columnValues);
    }
}
```

这样操作`$client`或`$hbase`时，编辑器就会给出对应的代码提示。

## 在其他框架中使用

### 配置文件

在对应的配置文件中添加如下配置信息，参数说明见上文

```php
return [
    'host' => "localhost",
    'port' => 9190,
    'key_id' => 'root',
    'signature' => 'root',
];
```

### 使用

```php
$hbase = new Luffy\AliHbaseThrift\Service\AliHbaseThriftService($config['host'], $config['port'], $config['key_id'], $config['signature']);
```

## Contributors

<table>
  <tr>
    <td align="center"><a href="https://github.com/zzss-utils"><img src="https://avatars3.githubusercontent.com/u/26597775?v=4" width="100px;" alt=""/><br /><sub><b>zhouhua</b></sub></a><br /><a href="https://github.com/sy-records/thrift2-hbase/commits?author=zzss-utils" title="Code">💻</a></td>
    <td align="center"><a href="http://qq52o.me"><img src="https://avatars3.githubusercontent.com/u/33931153?v=4" width="100px;" alt=""/><br /><sub><b>沈唁</b></sub></a><br /><a href="https://github.com/sy-records/thrift2-hbase/commits?author=sy-records" title="Code">💻</a> <a href="https://github.com/sy-records/thrift2-hbase/commits?author=sy-records" title="Documentation">📖</a></td>
    <td align="center"><a href="https://github.com/hejunrex"><img src="https://avatars3.githubusercontent.com/u/16148193?v=4" width="100px;" alt=""/><br /><sub><b>hejunrex</b></sub></a><br /><a href="https://github.com/sy-records/thrift2-hbase/commits?author=hejunrex" title="Code">💻</a></td>
    <td align="center"><a href="https://github.com/starfalling"><img src="https://avatars3.githubusercontent.com/u/532951?v=4" width="100px;" alt=""/><br /><sub><b>starfalling</b></sub></a><br /><a href="https://github.com/sy-records/thrift2-hbase/commits?author=starfalling" title="Code">💻</a></td>
  </tr>
</table>

## 扩展服务

此仓库是作为操作`Hbase`基础库发布的，另有完整的`Hbase`+`Solr`协程支持组件，操作更加便捷。
