<?php

namespace Zfegg\ModelManager\DataSource;


class StaticSource implements DataSourceInterface
{

    /**
     * 获取资源下的所有数据表
     *
     * @return array
     */
    public function getTables()
    {
        // TODO: Implement getTables() method.
    }

    /**
     * @param array $where
     * @param array $sort
     * @return \Zend\Paginator\Paginator
     */
    public function fetchPaginator($where = [], $sort = [])
    {
        // TODO: Implement fetchPaginator() method.
    }

    public function select($where = [], $sort = [])
    {
        // TODO: Implement select() method.
    }

    public function setDataConfig(array $config)
    {
        // TODO: Implement setDataConfig() method.
    }

    /**
     * 调用方法
     *
     * @param $method
     * @param array $row
     * @return array
     */
    public function call($method, array $row)
    {
        // TODO: Implement call() method.
    }

    /**
     * 更新
     *
     * @param array $data
     * @return mixed
     */
    public function update(array $data)
    {
        // TODO: Implement update() method.
    }

    public function insert(array $data)
    {
        // TODO: Implement insert() method.
    }

    public function delete(array $data)
    {
        // TODO: Implement delete() method.
    }
}