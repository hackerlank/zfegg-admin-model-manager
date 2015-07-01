<?php

namespace Zfegg\ModelManager\Controller;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Metadata;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zfegg\ModelManager\InputFilter\DataSourceConfig\BaseConfigInputFilter;
use Zfegg\ModelManager\InputFilter\DataSourceConfig\DbInputFilter;
use Zfegg\ModelManager\InputFilter\DataSourceConfig\RestfulInputFilter;

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
                        'id'              => ['type' => 'number', 'editable' => false, 'nullable' => true],
                        'name'            => ['type' => 'string'],
                        'adapter_options' => ['type' => 'string', 'defaultValue' => ['driver_options' => []]],
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
                            "values"     => $adapters,
                            "filterable" => true,
                        ],
                    ],
                    'toolbar'        => [
                        ['name' => 'edit', 'text' => '编辑', 'attr' => 'data-action=edit disabled'],
                        ['name' => 'create', 'text' => '增加', 'attr' => 'data-action=create'],
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
        $paginator = $table->fetchPaginator();
        $paginator->setCurrentPageNumber($this->getRequest()->getPost('page', 1));

        return [
            'total' => $paginator->getTotalItemCount(),
            'data'  => $paginator->getCurrentItems()->toArray(),
        ];
    }

    public function addAction()
    {

        $baseConfigFilters = new BaseConfigInputFilter();

        $baseConfigFilters->setData($_REQUEST);

        if (!$baseConfigFilters->isValid()) {
            return array('errors' => $baseConfigFilters->getMessages());
        }

        $data = $baseConfigFilters->getValues();

        if ($data['adapter'] == 'Restful') {
            $filter2 = new RestfulInputFilter();
            $filter2->setData($_REQUEST);
            if (!$filter2->isValid()) {
                return ['errors' => $filter2->getMessages()];
            }

            $data += $filter2->getValues();
        } else {
            $filter2 = new DbInputFilter();
            $filter2->setData($this->getRequest()->getPost('driver_options', []));
            if (!$filter2->isValid()) {
                return ['errors' => $filter2->getMessages()];
            }

            $data += ['driver_options' => $filter2->getValues()];
        }


        $table  = $this->getDataSourceConfigTable();
        $config = array(
            'name'    => $data['name'],
            'adapter' => $data['adapter'],
        );

        unset($data['name'], $data['adapter']);
        $config['adapter_options'] = json_encode($data);
        $table->insert($config);

        return new JsonModel(['code' => 1]);
    }

    public function testDbConnectionAction()
    {
        $filters = new DbInputFilter();
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
                foreach ($columns as $column) {
                    if (strpos($column->getDataType(), 'int') !== false) {
                        $type = 'int';
                    } else if (strpos($column->getDataType(), 'date') !== false ) {
                        $type = 'date';
                    } else if (strtolower($column->getDataType()) == 'timestamp') {
                        $type = 'timestamp';
                    } else {
                        $type = 'string';
                    }

                    $tables[$table->getName()][$column->getName()] = [
                        'nullable' => $column->getIsNullable(),
                        'default' => $column->getColumnDefault(),
                        'type' => $type,
                    ];
                }
            }

            return new JsonModel(['tables' => $tables]);
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