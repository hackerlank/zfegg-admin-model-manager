<?php

namespace Zfegg\ModelManager\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class SourceController extends AbstractActionController
{

    public function viewAction()
    {
        if (!$id = $this->params('id')) {
            $this->notFoundAction();
        }

        $table = $this->getUiConfigTable();

        $config = $table->loadConfig($id);

        return new ViewModel(['id' => $id, 'config' => $config]);
    }

    public function readAction()
    {
        if (!$id = $this->params('id')) {
            $this->notFoundAction();
        }

        $config                = $this->getUiConfigTable()->loadConfig($id);
        $dataSourceManager     = $this->get('Zfegg\ModelManager\DataSourceManager');
        $dataSourceConfigTable = $this->get('Zfegg\ModelManager\DataSourceConfigTable');
        $dataSourceConfig      = $dataSourceConfigTable->select(['name' => $config['source']])->current();

        try {
            /** @var \Zfegg\ModelManager\DataSource\DataSourceInterface $dataSource */
            $dataSource = $dataSourceManager->get(
                $dataSourceConfig['adapter'],
                (array)json_decode($dataSourceConfig['adapter_options'], true)
            );

            $dataSource->setDataConfig((array)$config['source_config']);
            $paginator = $dataSource->fetchPaginator($this->ui()->filter());
        } catch (\Exception $e) {
            return new JsonModel(['error' => $e->getMessage()]);
        }

        return new JsonModel([
            'total' => $paginator->getTotalItemCount(),
            'data'  => (array) $paginator->getCurrentItems(),
        ]);
    }

    /**
     * @return \Zfegg\ModelManager\Model\UiConfigTable
     */
    public function getUiConfigTable()
    {
        return $this->get('Zfegg\ModelManager\UiConfigTable');
    }
}