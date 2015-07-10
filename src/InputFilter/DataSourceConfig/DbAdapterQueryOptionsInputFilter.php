<?php

namespace Zfegg\ModelManager\InputFilter\DataSourceConfig;


use Zend\InputFilter\InputFilter;

class DbAdapterQueryOptionsInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(
            [
                'name' => 'mode',
                'validators' => [
                    [
                        'name' => 'InArray',
                        'options' => [
                            'haystack' => [
                                'normal',
                                'sql',
                            ]
                        ]
                    ]
                ]
            ]
        );
        $this->add(
            ['name' => 'table']
        );
        $this->add(
            ['name' => 'sql']
        );
    }

}