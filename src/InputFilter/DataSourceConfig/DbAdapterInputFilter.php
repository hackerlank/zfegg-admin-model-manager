<?php

namespace Zfegg\ModelManager\InputFilter\DataSourceConfig;
use Zend\InputFilter\InputFilter;

class DbAdapterInputFilter extends InputFilter
{

    public function __construct()
    {
        $this->add(
            [
                'name'    => 'driver',
                'filters' => [
                    ['name' => 'StringTrim']
                ],
                'validators' => [
                    [
                        'name' => 'InArray',
                        'options' => [
                            'haystack' => [
                                'pdo_mysql',
                                'ibmdb2',
                                'pdo_oci',
                                'pdo_pgsql',
                                'pdo_sqlite',
                                'pdo_sqlsrv',
                            ]
                        ]
                    ]
                ]
            ]
        );
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