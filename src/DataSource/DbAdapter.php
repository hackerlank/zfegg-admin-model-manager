<?php

namespace Zfegg\ModelManager\DataSource;
use Gzfextra\Stdlib\OptionsTrait;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Metadata;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class DbAdapter implements DataSourceInterface
{
    use OptionsTrait;

    protected $driverOptions = [];
    protected $metadata;
    protected $dbAdapter;


    public function __construct($options)
    {
        $this->setOptions($options);
    }

    /**
     * @return array
     */
    public function getDriverOptions()
    {
        return $this->driverOptions;
    }

    /**
     * @param array $driverOptions
     * @return $this
     */
    public function setDriverOptions(array $driverOptions)
    {
        $driverOptions = array_filter($driverOptions);
        $this->driverOptions = $driverOptions;
        return $this;
    }

    /**
     * @return Adapter
     */
    public function getDbAdapter()
    {
        if (!$this->dbAdapter) {
            $adapter = new Adapter($this->getDriverOptions());
            $this->setDbAdapter($adapter);
        }
        return $this->dbAdapter;
    }

    /**
     * @param mixed $dbAdapter
     * @return $this
     */
    public function setDbAdapter($dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        return $this;
    }


    public function getMetadata()
    {
        if (!$this->metadata) {

            $metadata = new Metadata($this->getDbAdapter());
            $this->metadata = $metadata;
        }

        return $this->metadata;
    }

    public function getTables()
    {
        $tables = [];
        foreach ($this->getMetadata()->getTables() as $table) {
            foreach ($table->getColumns() as $column) {
                $tables[$table->getName()][] = $column->getName();
            }
        }

        return $tables;
    }

    /**
     * @param Where|array $where
     * @param array $sort
     * @return Paginator
     */
    public function fetchPaginator($where = [], $sort = [])
    {
        $config = $this->dataConfig;
        if ($config['query_type'] == 'normal') {
            $table = new TableGateway($config['table'], $this->getDbAdapter());

            $columns = [];
            foreach ($config['column_enable'] as $column => $enable) {
                if ($enable) {
                    $alias = $config['column_alias'][$column] ? : $column;

                    $columns[$alias] = $column;
                }
            }

            $select = $table->getSql()->select();
            $select->columns($columns);
            if (!empty($where)) {
                $select->where($where);
            }
            if (!empty($sort)) {
                $select->order($sort);
            }

            return new Paginator($adapter = new DbSelect($select, $this->getDbAdapter()));
        } else {
            $params = [];
            if (!empty($where)) {
                $whereData = $where->getExpressionData();
                foreach ($whereData as $key => $val) {
                    //todo fix sql mode
                }
            }
            return $this->getDbAdapter()->query($config['sql'], $params)->toArray();
        }
    }

    protected $dataConfig;

    public function setDataConfig(array $config)
    {
        $this->dataConfig = $config;
    }

    public function call($method, array $row)
    {
        ;
    }

    public function update(array $data)
    {
        ;
    }

    public function insert(array $data)
    {

    }

    public function select($where = [], $sort = [])
    {
        // TODO: Implement select() method.
    }

    public function delete(array $data)
    {
        // TODO: Implement delete() method.
    }
}