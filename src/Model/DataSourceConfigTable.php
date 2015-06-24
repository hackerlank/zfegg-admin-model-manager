<?php
/**
 * Zend Framework Egg (https://github.com/zfegg)
 *
 * @link      https://github.com/zfegg for the canonical source repository
 */
 

namespace Zfegg\ModelManager\Model;


use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class DataSourceConfigTable extends TableGateway
{
    public function fetchPaginator($where = null)
    {
        $select = $this->getSql()->select();
        $resultSet = new HydratingResultSet();
        $adapter = new DbSelect($select, $this->getAdapter(), $this->getResultSetPrototype());

        return new Paginator($adapter);
    }

}