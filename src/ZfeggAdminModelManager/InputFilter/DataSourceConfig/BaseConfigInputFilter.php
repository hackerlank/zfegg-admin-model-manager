<?php

namespace Zfegg\ModelManager\InputFilter\DataSourceConfig;

use Zend\Db\TableGateway\TableGateway;
use Zend\InputFilter\ArrayInput;
use Zend\InputFilter\InputFilter;

class BaseConfigInputFilter extends InputFilter
{

    public function __construct($adapter, TableGateway $table)
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
                'validators' => [
                    [
                        'name'    => 'Db\NoRecordExists',
                        'options' => [
                            'table'   => $table->getTable(),
                            'field'   => 'name',
                            'adapter' => $table->getAdapter()
                        ],
                    ],
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
            $adapterOptionsFilter->add($this->getQueryOptionsInputFilter(), 'query_options');

            $this->add($adapterOptionsFilter, 'adapter_options');
        } else if ($adapter == 'JsonRpc') {
            $this->add([
                'type' => InputFilter::class,
                [
                    'name' => 'url',
                    'validators' => [
                        [
                            'name' => 'uri',
                            'options' => [
                                'allowRelative' => false,
                            ]
                        ]
                    ]
                ]
            ], 'adapter_options');
        }

        $this->add(
            [
                'type'       => ArrayInput::class,
                'name'       => 'fields',
                'filters'    => [
                    [
                        'name'    => 'callback',
                        'options' => [
                            'callback' => function ($val) {
                                $types = ['string', 'number', 'date', 'boolean'];

                                if (isset($val['type']) && in_array($val['type'], $types)) {
                                    $return['type'] = $val['type'];
                                }
                                if (isset($val['defaultValue']) && $val['defaultValue'] != '') {
                                    $return['defaultValue'] = $val['defaultValue'];
                                }
                                $return['editable'] = isset($val['editable']) && $val['editable'];
                                $return['nullable'] = isset($val['nullable']) && $val['nullable'];

                                if (isset($val['primary'])) {
                                    $return['primary'] = true;
                                }

                                return $return;
                            }
                        ]
                    ]
                ]
            ]
        );
    }


    private function getQueryOptionsInputFilter()
    {

        $inputs   = [];
        $inputs[] = [
            'name'       => 'mode',
            'validators' => [
                [
                    'name'    => 'InArray',
                    'options' => [
                        'haystack' => [
                            'normal',
                            'sql',
                        ]
                    ]
                ]
            ]
        ];
        $inputs[] = ['name' => 'table', 'required' => false];
        $inputs[] = ['name' => 'sql', 'required' => false];
        $inputs[] = [
            'type'     => ArrayInput::class,
            'name'     => 'column',
            'required' => false,
        ];
        $inputs[] = [
            'type'     => ArrayInput::class,
            'name'     => 'order',
            'required' => false
        ];

        $inputFilter = new InputFilter();

        foreach ($inputs as $input) {
            $inputFilter->add($input);
        }


        $whereInputs = [
            'type' => InputFilter::class,
            [
                'type' => ArrayInput::class,
                'name' => 'left',
            ],
            [
                'type'       => ArrayInput::class,
                'name'       => 'operator',
                'validators' => [
                    [
                        'name'    => 'InArray',
                        'options' => [
                            'haystack' => ["=", "<>", "<", "<=", ">", ">=", "like", "notLike", "isNull", "isNotNull"]
                        ]
                    ]
                ]
            ],
            [
                'type' => ArrayInput::class,
                'name' => 'right',
            ]
        ];
        $inputFilter->add($whereInputs, 'where');

        return $inputFilter;
    }
//
//    private function FieldsInputFilter()
//    {
//        $inputs         = [
//            [
//                'type' => ArrayInput::class,
//                'name'       => 'type',
//                'validators' => [
//                    [
//                        'name'    => 'InArray',
//                        'options' => [
//                            'haystack' => ['string', 'number', 'date', 'boolean']
//                        ]
//                    ]
//                ]
//            ],
//            [
//                'type' => ArrayInput::class,
//                'name' => 'defaultValue',
//                'allow_empty' => true,
//            ],
//            [
//                'type'    => ArrayInput::class,
//                'name'    => 'editable',
//                'filters' => [
//                    ['name' => 'Boolean',]
//                ],
//            ],
//            [
//                'type'    => ArrayInput::class,
//                'name'    => 'nullable',
//                'filters' => [
//                    ['name' => 'Boolean',]
//                ],
//            ],
//        ];
//        $inputs['type'] = InputFilter::class;
//
//        return $inputs;
//    }

}