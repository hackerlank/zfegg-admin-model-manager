<?php

namespace Zfegg\ModelManager\Controller;

use Zfegg\ModelManager\InputFilter\UiConfig\DbUiConfigInputFilter;
use Zfegg\ModelManager\InputFilter\UiConfigInputFilter;
use Zend\Db\Sql\Select;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class UiConfigController extends AbstractActionController
{
    public function initAction()
    {

        /** @var \Zend\Db\TableGateway\TableGateway $table */
        $table = $this->get('Zfegg\ModelManager\DataSourceConfigTable');

        $result = $table->select(
            function (Select $select) {
                $select->columns(['name', 'adapter']);
            }
        );

        return new JsonModel([
            'data_source' => $result->toArray(),
        ]);
    }

    public function readAction()
    {
        $table   = $this->getUiConfigTable();

        /** @var \Zend\Paginator\Paginator $paginator */
        $paginator = $table->fetchPaginator(
            function (Select $select) {
                $select->columns(['id', 'name', 'source']);
            }
        );
        $paginator->setCurrentPageNumber($this->getRequest()->getPost('page', 1));

        return new JsonModel([
            'total' => $paginator->getTotalItemCount(),
            'data'  => $paginator->getCurrentItems()->toArray(),
        ]);
    }

    public function getTablesAction()
    {
        /** @var \Zfegg\ModelManager\DataSource\DataSourceManager $dataSourceManager */
        $dataSourceManager     = $this->get('Zfegg\ModelManager\DataSourceManager');
        $dataSourceConfigTable = $this->get('Zfegg\ModelManager\DataSourceConfigTable');
        $dataSourceConfig      = $dataSourceConfigTable->select(['name' => $this->params('name')])->current();

        $error = null;
        try {
            /** @var \Zfegg\ModelManager\DataSource\DataSourceInterface $dataSource */
            $dataSource = $dataSourceManager->get(
                $dataSourceConfig['adapter'],
                (array)json_decode($dataSourceConfig['adapter_options'], true)
            );

            $tables = $dataSource->getTables();
        } catch (\Exception $e) {
            $tables = [];
            $error  = $e->getMessage();
        }

        return new JsonModel([
            'tables' => $tables,
            'error'  => $error
        ]);
    }

    public function saveAction()
    {
        $filters = new UiConfigInputFilter();
        $filters->setData($_REQUEST);

        if (!$filters->isValid()) {
            return new JsonModel(['errors' => $filters->getMessages()]);
        }

        $data = $filters->getValues();

        if ($data['source_adapter'] != 'Restful') {
            $filters2 = new DbUiConfigInputFilter();
            $filters2->setData($this->getRequest()->getPost('source_config'));
            if (!$filters2->isValid()) {
                return new JsonModel(['errors' => $filters2->getMessages()]);
            }

            $data += ['source_config' => json_encode($filters2->getValues())];
        }
        unset($data['source_adapter']);

        $table = $this->getUiConfigTable();
        $table->insert($data);

        return new JsonModel(['code' => 1]);
    }

    /**
     * @return \Zfegg\ModelManager\Model\UiConfigTable
     */
    public function getUiConfigTable()
    {
        return $this->get('Zfegg\ModelManager\UiConfigTable');
    }

}