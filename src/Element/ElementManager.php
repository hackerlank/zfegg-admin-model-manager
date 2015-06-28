<?php

namespace Zfegg\ModelManager\Element;


use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;

class ElementManager extends AbstractPluginManager
{
    protected $invokableClasses = [
        'DropDownList' => 'Zfegg\ModelManager\Element\DropDownList',
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
        if (!$plugin instanceof ElementInterface) {
            throw new Exception\RuntimeException(sprintf(
                'Plugin of type %s is invalid; must implement %s\ElementInterface',
                (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
                __NAMESPACE__
            ));
        }
    }
}