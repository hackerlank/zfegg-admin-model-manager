<?php

namespace Zfegg\ModelManager\Model;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zfegg\ModelManager\Db\CallbackResultSet;

class UiConfigTable extends TableGateway implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function __construct($table, AdapterInterface $adapter, $features = null, ResultSetInterface $resultSetPrototype = null, Sql $sql = null)
    {
        if ($resultSetPrototype === null) {
            $resultSetPrototype = new CallbackResultSet();
            $resultSetPrototype->setCallable(function ($data) {
                if (isset($data['columns'])) {
                    $data['columns'] = json_decode($data['columns'], true);
                }

                return $data;
            });
        }

        parent::__construct($table, $adapter, $features, $resultSetPrototype, $sql);
    }

    public function loadConfig($id)
    {

        $dscTableName = $this->getServiceLocator()->get('Zfegg\ModelManager\DataSourceConfigTable')->getTable();

        $resultSetPrototype = $this->getResultSetPrototype();
        $resultSetPrototype->setCallable(function ($data) {
            if (isset($data['columns'])) {
                $data['columns'] = json_decode($data['columns'], true);

                if (is_array($data['columns'])) {
                    foreach ($data['columns'] as $key => $attrs) {
                        if (!empty($attrs['primary'])) {
                            $data['primary'] = true;
                        }
                    }
                }
            }
            if (isset($data['fields'])) {
                $data['fields'] = json_decode($data['fields'], true);
            }
            return $data;
        });

        return $this->select(function (Select $select) use ($dscTableName, $id) {
            $select->columns(['name', 'columns', 'detail_template']);
            $select->join(['b' => $dscTableName], $this->getTable() . '.source=b.name', ['fields']);
            $select->where([$this->getTable() . '.id' => $id]);
        })->current();
    }

    public function insert($data)
    {
        $data['columns'] = json_encode($data['columns']);
        parent::insert($data);
    }
}