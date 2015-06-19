<?php

namespace Zfegg\ModelManager\DataSource;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;

class DataSourceManager extends AbstractPluginManager
{
    protected $invokableClasses = [
        'mysql'   => 'Zfegg\ModelManager\DataSource\Mysql',
        'oracle'  => 'Zfegg\ModelManager\DataSource\Oracle',
        'ibmDB2'  => 'Zfegg\ModelManager\DataSource\IbmDB2',
        'sqlite'  => 'Zfegg\ModelManager\DataSource\Sqlite',
        'pgsql'   => 'Zfegg\ModelManager\DataSource\Pgsql',
        'sqlsrv'  => 'Zfegg\ModelManager\DataSource\Sqlsrv',
        'restful' => 'Zfegg\ModelManager\DataSource\Restful',
    ];

    /**
     * Validate the plugin
     *
     * Checks that the filter loaded is either a valid callback or an instance
     * of FilterInterface.
     *
     * @param  mixed $plugin
     * @return void
     * @throws Exception\RuntimeException if invalid
     */
    public function validatePlugin($plugin)
    {
        if (!$plugin instanceof DataSourceInterface) {
            throw new \InvalidArgumentException(sprintf(
                'Plugin of type %s is invalid; must implement %s\DataSourceInterface',
                (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
                __NAMESPACE__
            ));
        }
    }
}