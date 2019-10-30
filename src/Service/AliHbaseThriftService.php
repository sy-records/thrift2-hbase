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
    public function __construct($host,$port,$key_id,$signature)
    {
        $headers = array('ACCESSKEYID' => $key_id,'ACCESSSIGNATURE' => $signature);

        $socket = new THttpClient($host, $port);
        $socket->addHeaders($headers);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $this->client = new THBaseServiceClient($protocol);
		$transport->open();
    }

    /**
     * @return THBaseServiceClient
     */
    public function getClient()
    {
        return $this->client;
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