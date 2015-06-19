<?php

namespace Zfegg\ModelManager\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    public function uiAction()
    {
        $view = new ViewModel();
        $view->setTemplate(sprintf('model-manager/%s/%s', $this->params('ctrl'), $this->params('name')));
        return $view;
    }
}