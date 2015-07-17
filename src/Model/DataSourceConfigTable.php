<?php
/**
 * Zend Framework Egg (https://github.com/zfegg)
 *
 * @link      https://github.com/zfegg for the canonical source repository
 */
 

namespace Zfegg\ModelManager\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zfegg\ModelManager\Db\CallbackResultSet;

class DataSourceConfigTable extends TableGateway
{

    public function __construct($table, AdapterInterface $adapter, $features = null)
    {
        $resultSet = new CallbackResultSet();
        $resultSet->setCallable(function ($data) {
            if (isset($data['adapter_options'])) {
                $data['adapter_options'] = json_decode($data['adapter_options'], true);
            }
            if (isset($data['fields'])) {
                $data['fields'] = json_decode($data['fields'], true);
            }

            return $data;
        });
        parent::__construct($table, $adapter, $features, $resultSet, null);
    }

    public function fetchPaginator($where = null)
    {
        $select = $this->getSql()->select();

        if ($where instanceof \Closure) {
            $where($select);
        } elseif ($where !== null) {
            $select->where($where);
        }

        $adapter = new DbSelect($select, $this->getAdapter(), $this->getResultSetPrototype());

        return new Paginator($adapter);
    }

    public function insert($data)
    {
        $data['adapter_options'] = json_encode($data['adapter_options']);
        $data['fields'] = json_encode($data['fields']);
        parent::insert($data);
    }
}