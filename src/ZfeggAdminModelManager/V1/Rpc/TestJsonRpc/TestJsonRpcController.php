<?php
namespace ZfeggAdminModelManager\V1\Rpc\TestJsonRpc;

use Zend\Json\Server\Client;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class TestJsonRpcController extends AbstractActionController
{
    public function testJsonRpcAction()
    {
        $filters = $this->getInputfilter();

        try {
            $client = new Client($filters->getValue('url'));
            return new JsonModel(['fields' => $client->call('meta')]);
        } catch (\Exception $e) {
            return new JsonModel(['errors' => $e->getMessage()]);
        }
    }
}
