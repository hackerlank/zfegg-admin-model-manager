<?php

namespace Zfegg\ModelManager\InputFilter\DataSourceConfig;

use Zend\InputFilter\InputFilter;

class BaseConfigInputFilter extends InputFilter
{

    public function __construct($adapter)
    {
        $adapters = [
            'DbAdapter',
            'JsonRpc',
        ];
        $this->add(
            [
                'name'    => 'name',
                'filters' => [
                    ['name' => 'StringTrim']
                ],
            ]
        );
        $this->add(
            [
                'name'       => 'adapter',
                'validators' => [
                    [
                        'name'    => 'InArray',
                        'options' => [
                            'haystack' => $adapters,
                        ]
                    ]
                ],
            ]
        );

        if ($adapter == 'DbAdapter') {
            $adapterOptionsFilter = new InputFilter();
            $adapterOptionsFilter->add(new DbAdapterInputFilter(), 'driver_options');
            $adapterOptionsFilter->add(new DbAdapterQueryOptionsInputFilter(), 'query_options');

            $this->add($adapterOptionsFilter, 'adapter_options');
        }
    }

}