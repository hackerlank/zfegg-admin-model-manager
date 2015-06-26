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
        return ['__HTTP__' => $this->getRpcClient()->call('meta')];
    }

    /**
     * @param array|\Zend\Db\Sql\Where $where
     * @param array $sort
     * @return Paginator
     */
    public function read($where = [], $sort = [])
    {
        $adapter = new Callback(
            function ($offset, $itemCountPerPage) use ($where, $sort) {
                $results = $this->getRpcClient()->call('select', [$where, $sort, $offset, $itemCountPerPage]);

                if (!(isset($results['meta']) && isset($results['rows']))) {
                    throw new \RuntimeException('RPC接口返回数据不符合接口规范');
                }

                $columns = array_keys($results['meta']);
                foreach ($results['rows'] as $i => $row) {
                    $results['rows'][$i] = array_combine($columns, $row);
                }

                return $results['rows'];
            },

            function () use ($where) {
                return (int)$this->getRpcClient()->call('fetchCount', [$where]);
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