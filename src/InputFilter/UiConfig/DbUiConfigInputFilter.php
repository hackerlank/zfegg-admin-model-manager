<?php

namespace Zfegg\ModelManager\InputFilter\UiConfig;

use Zend\InputFilter\InputFilter;

class DbUiConfigInputFilter extends InputFilter
{

    public function __construct()
    {
        $inputs = [
            [
                'name' => 'table',
            ],
            [
                'name' => 'query_type',
            ],
            [
                'type'    => 'Zend\InputFilter\ArrayInput',
                'name'    => 'column_enable',
                'filters' => [
                    ['name' => 'Boolean'],
                ],
            ],
            [
                'type'     => 'Zend\InputFilter\ArrayInput',
                'required' => false,
                'name'     => 'column_alias',
                'filters'  => [
                    ['name' => 'StringTrim'],
                ],
                'allow_empty' => true,
            ],
            [
                'required' => false,
                'name'     => 'sql',
            ],
        ];

        foreach ($inputs as $input) {
            $this->add($input);
        }
    }
}