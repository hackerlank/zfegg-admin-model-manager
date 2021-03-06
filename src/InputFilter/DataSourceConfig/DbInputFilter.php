<?php

namespace Zfegg\ModelManager\InputFilter\DataSourceConfig;
use Zend\InputFilter\InputFilter;

class DbInputFilter extends InputFilter
{

    public function __construct()
    {
        $inputFilter = new InputFilter();

        $this->add(
            [
                'name'    => 'hostname',
                'filters' => [
                    ['name' => 'StringTrim']
                ],
            ]
        );
        $this->add(
            [
                'name'    => 'username',
                'filters' => [
                    ['name' => 'StringTrim']
                ],
            ]
        );
        $this->add(
            [
                'required' => false,
                'name'    => 'password',
                'filters' => [
                    ['name' => 'StringTrim']
                ],
            ]
        );
        $this->add(
            [
                'required' => false,
                'name'    => 'port',
                'filters' => [
                    ['name' => 'StringTrim'],
                ],
            ]
        );
        $this->add(
            [
                'name'    => 'database',
                'filters' => [
                    ['name' => 'StringTrim']
                ],
            ]
        );
        $this->add(
            [
                'required' => false,
                'name'    => 'charset',
                'filters' => [
                    ['name' => 'StringTrim']
                ],
            ]
        );
    }
}