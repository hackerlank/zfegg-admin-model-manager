<?php

namespace Zfegg\ModelManager;


class Module
{
    const CONFIG_KEY = 'zfegg_model_manager';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}