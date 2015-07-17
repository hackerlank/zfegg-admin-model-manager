<?php

namespace Zfegg\ModelManager\InputFilter;

use Zend\InputFilter\ArrayInput;
use Zend\InputFilter\InputFilter;

class UiConfigInputFilter extends InputFilter
{

    public function __construct()
    {
        $columnsInputFilter = new InputFilter();
        $columnsInputFilter->add([
            'type' => ArrayInput::class,
            'name' => 'field',
        ]);
        $columnsInputFilter->add([
            'type' => ArrayInput::class,
            'name' => 'title',
            'required' => false,
            'allow_empty' => true,
        ]);
        $columnsInputFilter->add([
            'type' => ArrayInput::class,
            'name' => 'template',
            'required' => false,
            'allow_empty' => true,
        ]);
        $columnsInputFilter->add(
            [
                'type' => ArrayInput::class,
                'name'    => 'width',
                'filters' => [
                    ['name' => 'ToInt']
                ],
                'required' => false,
                'allow_empty' => true,
            ]
        );
        $columnsInputFilter->add(
            [
                'type' => ArrayInput::class,
                'name'    => 'filterable',
                'filters' => [
                    ['name' => 'Boolean']
                ],
            ]
        );
        $columnsInputFilter->add(
            [
                'type' => ArrayInput::class,
                'name'    => 'sortable',
                'filters' => [
                    ['name' => 'Boolean']
                ],
            ]
        );

        $inputs = [
            //
            [
                'name' => 'name',
            ],
            [
                'name' => 'source',
            ],

            //
            [
                'required' => false,
                'name'     => 'detail_template',
            ],
        ];

        foreach ($inputs as $input) {
            $this->add($input);
        }

        $this->add($columnsInputFilter, 'columns');
    }
}