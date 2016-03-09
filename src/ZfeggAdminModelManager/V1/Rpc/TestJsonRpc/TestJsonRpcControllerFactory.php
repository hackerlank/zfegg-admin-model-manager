<?php
namespace ZfeggAdminModelManager\V1\Rpc\TestJsonRpc;

class TestJsonRpcControllerFactory
{
    public function __invoke($controllers)
    {
        return new TestJsonRpcController();
    }
}
