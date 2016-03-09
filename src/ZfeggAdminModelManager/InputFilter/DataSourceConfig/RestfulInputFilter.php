<?php

namespace Zfegg\ModelManager\InputFilter\DataSourceConfig;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\ArrayInput;

class RestfulInputFilter extends InputFilter
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
        $this->add(
            [
                'type'    => 'Zend\InputFilter\ArrayInput',
                'name'    => 'fields',
                'filters' => [
                    ['name' => 'StringTrim']
                ],
            ]
        );
    }
}