<?php
namespace ZfeggAdminModelManager\V1\Rpc\TestDbConnection;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\Metadata\Source\Factory as MetadataFactory;
use Zend\View\Model\JsonModel;

class TestDbConnectionController extends AbstractActionController
{
    public function testDbConnectionAction()
    {
        $filters = $this->getInputfilter();

        try {
            $data     = array_filter($filters->getValues());
            $adapter  = new Adapter($data);
            $metadata = MetadataFactory::createSourceFromAdapter($adapter);

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
}
