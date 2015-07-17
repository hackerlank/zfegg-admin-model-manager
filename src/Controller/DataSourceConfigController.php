<?php

namespace Zfegg\ModelManager\Controller;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Metadata;
use Zend\Db\Sql\Select;
use Zend\Json\Server\Client;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\Uri;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zfegg\ModelManager\InputFilter\DataSourceConfig\BaseConfigInputFilter;
use Zfegg\ModelManager\InputFilter\DataSourceConfig\DbAdapterInputFilter;
use Zfegg\ModelManager\InputFilter\DataSourceConfig\JsonRpcInputFilter;

class DataSourceConfigController extends AbstractActionController
{

    public function indexAction()
    {
        $adapters = [
            'DbAdapter',
            'JsonRpc',
        ];

        return new ViewModel(
            [
                'adapters' => $adapters,
                'id'       => $this->params('__CONTROLLER__'),
                'config'   => [
                    'primary'        => 'id',
                    'fields'         => [
                        'id'      => ['type' => 'number', 'editable' => false, 'nullable' => true],
                        'name'    => ['type' => 'string'],
                        'adapter' => ['type' => 'string'],
                    ],
                    'columns'        => [
                        [
                            "field"      => "id",
                            "title"      => "ID",
                            "sortable"   => true,
                            "filterable" => true,
                            "width"      => 100,
                        ],
                        [
                            "field"      => "name",
                            "title"      => "名称",
                            "filterable" => true,
                            "width"      => 250,
                        ],
                        [
                            "field"      => "adapter",
                            "title"      => "适配器",
//                            "values"     => $adapters,
                            "filterable" => true,
                        ],
                    ],
                    'toolbar'        => [
                        ['name' => 'edit', 'text' => '编辑', 'attr' => 'data-action=edit disabled'],
//                        ['name' => 'create', 'text' => '增加', 'attr' => 'data-action=create'],
                        ['name' => 'destroy', 'text' => '删除', 'attr' => 'data-action=destroy'],
                    ],
                    'detailEnable'   => true,
                    'detailTemplate' => '#:data.adapter_options#'
                ],
            ]
        );
    }

    public function readAction()
    {
        $table = $this->getDataSourceConfigTable();

        /** @var \Zend\Paginator\Paginator $paginator */
        $paginator = $table->fetchPaginator(function (Select $select) {
            $select->columns(['id', 'name', 'adapter']);
        });
        $paginator->setCurrentPageNumber($this->getRequest()->getPost('page', 1));

        return new JsonModel(
            [
                'total' => $paginator->getTotalItemCount(),
                'data'  => (array)$paginator->getCurrentItems(),
            ]
        );
    }

    public function addAction()
    {
        if (!$this->getRequest()->isPost()) {
            return new ViewModel();
        }

        $baseConfigFilters = new BaseConfigInputFilter(
            $this->getRequest()->getPost('adapter'),
            $this->getDataSourceConfigTable()
        );

        $baseConfigFilters->setData($_REQUEST);

        if (!$baseConfigFilters->isValid()) {
            return new JsonModel(['errors' => $baseConfigFilters->getMessages()]);
        }

        $data = $baseConfigFilters->getValues();

        $table = $this->getDataSourceConfigTable();
        $table->insert($data);

        return new JsonModel(['code' => 0]);
    }

    public function testDbConnectionAction()
    {
        $filters = new DbAdapterInputFilter();
        $filters->setData(
            isset($_REQUEST['adapter_options']['driver_options']) ? $_REQUEST['adapter_options']['driver_options'] : []
        );

        if (!$filters->isValid()) {
            return new JsonModel(['errors' => $filters->getMessages()]);
        }

        try {
            $data     = array_filter($filters->getValues());
            $adapter  = new Adapter($data);
            $metadata = new Metadata($adapter);

            $tables = [];
            foreach ($metadata->getTables() as $table) {
                /** @var \Zend\Db\Metadata\Object\ColumnObject[] $columns */
                $columns = $table->getColumns();
                foreach ($columns as $key => $column) {
                    if (strpos($column->getDataType(), 'int') !== false) {
                        $type = 'number';
                    } else if (strpos($column->getDataType(), 'date') !== false) {
                        $type = 'date';
                    } else if (strtolower($column->getDataType()) == 'timestamp') {
                        $type = 'date';
                    } else {
                        $type = 'string';
                    }

                    $tables[$table->getName()][$column->getName()] = [
                        'nullable'     => $column->getIsNullable(),
                        'defaultValue' => $column->getColumnDefault() == 'null' ? null : $column->getColumnDefault(),
                        'type'         => $type,
                    ];

                    if ($key == 0 && strpos($column->getName(), 'id') !== false) {
                        $tables[$table->getName()][$column->getName()]['primary'] = true;
                    }
                }
            }

            return new JsonModel(['tables' => $tables]);
        } catch (\Exception $e) {
            return new JsonModel(['errors' => $e->getMessage()]);
        }
    }

    public function testJsonrpcAction()
    {
        $filters = new JsonRpcInputFilter();
        $filters->setData($_REQUEST);

        if (!$filters->isValid()) {
            return new JsonModel(['errors' => $filters->getMessages()]);
        }

        try {
            $client = new Client($filters->getValue('url'));
            return new JsonModel(['fields' => $client->call('meta')]);
        } catch (\Exception $e) {
            return new JsonModel(['errors' => $e->getMessage()]);
        }
    }

    /**
     * @return \Zfegg\ModelManager\Model\DataSourceConfigTable
     */
    public function getDataSourceConfigTable()
    {
        return $this->get('Zfegg\ModelManager\DataSourceConfigTable');
    }
}