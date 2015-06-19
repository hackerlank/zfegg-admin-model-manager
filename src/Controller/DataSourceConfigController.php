<?php

namespace Zfegg\ModelManager\Controller;

use Zfegg\ModelManager\InputFilter\DataSourceConfig\BaseConfigInputFilter;
use Zfegg\ModelManager\InputFilter\DataSourceConfig\DbInputFilter;
use Zfegg\ModelManager\InputFilter\DataSourceConfig\RestfulInputFilter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class DataSourceConfigController extends AbstractActionController
{

    public function addAction()
    {

        $baseConfigFilters = new BaseConfigInputFilter();

        $baseConfigFilters->setData($_REQUEST);

        if (!$baseConfigFilters->isValid()) {
            return array('errors' => $baseConfigFilters->getMessages());
        }

        $data   = $baseConfigFilters->getValues();

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


        $table  = $this->get('ModelManager\DataSourceConfigTable');
        $config = array(
            'name'    => $data['name'],
            'adapter' => $data['adapter'],
        );

        unset($data['name'], $data['adapter']);
        $config['adapter_options'] = json_encode($data);
        $table->insert($config);

        return new JsonModel(['code' => 1]);
    }
}