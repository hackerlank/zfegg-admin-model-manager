<?php


namespace Zfegg\ModelManager\DataSource;


interface DataSourceInterface {

    /**
     * 获取资源下的所有数据表
     * @return array
     */
    public function getTables();


    /**
     * @param array $where
     * @param array $sort
     * @return \Zend\Paginator\Paginator
     */
    public function fetchPaginator($where = [], $sort = []);

    public function select($where = [], $sort = []);

    public function setDataConfig(array $config);

    /**
     * 调用方法
     * @param $method
     * @param array $row
     * @return array
     */
    public function call($method, array $row);

    /**
     * 更新
     * @param array $row
     * @return mixed
     */
    public function update(array $row);
}