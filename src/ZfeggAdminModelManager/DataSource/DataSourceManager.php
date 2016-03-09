<?php

namespace Zfegg\ModelManager\DataSource;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;

class DataSourceManager extends AbstractPluginManager
{
    protected $invokableClasses = [
        'dbadapter' => 'Zfegg\ModelManager\DataSource\DbAdapter',
        'restful'   => 'Zfegg\ModelManager\DataSource\Restful',
        'jsonrpc'   => 'Zfegg\ModelManager\DataSource\JsonRpc',
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
            throw new \InvalidArgumentException(
                sprintf(
                    'Plugin of type %s is invalid; must implement %s\DataSourceInterface',
                    (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
                    __NAMESPACE__
                )
            );
        }
    }
}