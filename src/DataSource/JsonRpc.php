<?php

namespace Zfegg\ModelManager\DataSource;
use Gzfextra\Stdlib\OptionsTrait;
use Zend\Json\Server\Client;
use Zend\Paginator\Adapter\Callback;
use Zend\Paginator\Paginator;

class JsonRpc implements DataSourceInterface
{
    use OptionsTrait;

    protected $url, $fields = [], $rpcClient;

    public function __construct($options)
    {
        $this->setOptions($options);
    }

    /**
     * 获取资源下的所有数据表
     *
     * @return array
     */
    public function getTables()
    {
        return ['http' => $this->getFields()];
    }

    /**
     * @return \Zend\Paginator\Paginator
     */
    public function read()
    {
        $results = [];
        $getResults = function ($offset = null, $itemCountPerPage = null) use (&$results) {
            if (empty($results)) {
                $callResults = $this->getRpcClient()->call('select', []);

                if (!(isset($callResults['meta']) || isset($callResults['data']) || isset($callResults['total']))) {
                    throw new \RuntimeException('RPC接口返回数据不符合接口规范');
                }

                $columns = array_keys($callResults['meta']);
                foreach ($callResults['data'] as $i => $row) {
                    $callResults['data'][$i] = array_combine($columns, $row);
                }

                $results['data'] = $callResults['data'];
                $results['total'] = $callResults['total'];
            }

            return $results;
        };

        $adapter = new Callback(
            function ($offset, $itemCountPerPage) use ($getResults) {
                return $getResults($offset, $itemCountPerPage)['data'];
            }, function () use ($getResults) {
            return $getResults()['total'];
        }
        );

        return new Paginator($adapter);
    }

    public function setDataConfig(array $config)
    {
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    private function getRpcClient()
    {
        if (!$this->rpcClient) {
            $this->rpcClient = new Client($this->getUrl());
        }

        return $this->rpcClient;
    }
}