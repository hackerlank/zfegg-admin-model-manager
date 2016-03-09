<?php

namespace Zfegg\ModelManager\InputFilter\DataSourceConfig;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\ArrayInput;

class JsonRpcInputFilter extends InputFilter
{

    public function __construct()
    {

        $this->add(
            [
                'name'    => 'url',
                'filters' => [
                    ['name' => 'StringTrim']
                ],
            ]
        );
    }
}