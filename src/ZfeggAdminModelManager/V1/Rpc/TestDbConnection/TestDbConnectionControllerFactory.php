<?php
namespace ZfeggAdminModelManager\V1\Rpc\TestDbConnection;

class TestDbConnectionControllerFactory
{
    public function __invoke($controllers)
    {
        return new TestDbConnectionController();
    }
}
