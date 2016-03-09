<?php

namespace Zfegg\ModelManager\DataSource;
use Gzfextra\Stdlib\OptionsTrait;
use Zend\Http\ClientStatic;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Callback;

class Restful implements DataSourceInterface
{
    use OptionsTrait;

    protected $url, $fields = [];

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
        return ['restful' => $this->getFields()];
    }

    /**
     * @return \Zend\Paginator\Paginator
     */
    public function fetchPaginator()
    {
        $results = [];
        $getResults = function ($offset = null, $itemCountPerPage = null) use (&$results) {
            if (empty($results)) {
                $client = ClientStatic::get($this->getUrl(), ['offset' => $offset, 'limit' => $itemCountPerPage]);

                $results = json_decode($client->getBody(), true);
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
}