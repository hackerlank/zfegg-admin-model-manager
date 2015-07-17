<?php

return array(
    'router'       => array(
        'routes' => array(
            'zfegg-model-manager'    => array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/model-manager[/:controller[/:action]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults'    => array(
                        '__NAMESPACE__' => 'Zfegg\ModelManager',
                        'controller'    => 'index',
                        'action'        => 'index',
                    ),
                ),
                'child_routes' => array(
                    'params' => array(
                        'type' => 'Wildcard',
                    ),
                ),
            ),
            'zfegg-model-manager-ui' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/ui/model-manager[/:ctrl[/:name]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults'    => array(
                        '__NAMESPACE__' => 'Zfegg\ModelManager',
                        'controller'    => 'index',
                        'action'        => 'ui',
                    ),
                ),
            ),
        ),
    ),
    'zfc_rbac'     => [
        'guards' => [
            'Zfegg\Admin\Rbac\PermissionsGuard' => [
                'routes' => [
                    'zfegg-model-manager*'
                ],
            ]
        ]
    ],

    'zfegg_admin'   => array(
        'menus'                          => array(
            'Zfegg\ModelManager' => array(
                'text'     => '模型管理',
                'expanded' => true,
                'items'    => array(
                    array(
                        'text'  => '数据源添加',
                        'index' => 0,
                        'url'   => './model-manager/data-source-config/add',
                    ),
                    array(
                        'text'  => '数据源管理',
                        'index' => 0,
                        'url'   => './model-manager/data-source-config/index',
                    ),
                    array(
                        'text'  => '模型UI配置',
                        'index' => 1,
                        'url'   => './ui/model-manager/ui-config/index',
                    ),
                    array(
                        'text'  => '模型UI列表',
                        'index' => 1,
                        'url'   => './ui/model-manager/ui-config/list',
                    ),
                    array(
                        'text'  => '菜单配置',
                        'url'   => './model-manager/source/view/id/4',
                    ),
                    array(
                        'text'  => '数据源权限',
                        'url'   => './model-manager/source/view/id/2',
                    ),
                ),
            ),
        ),
        'permission_scan_controller_dir' => array(
            'Zfegg\ModelManager' => __DIR__ . '/../src/Controller',
        ),
    ),

    'moln_model_manager' => array(
        'data_source_manager' => array(
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'tables'       => array(
        'Zfegg\ModelManager\DataSourceConfigTable' => array(
            'table'   => 'model_manager_data_source_config',
            'primary' => 'id',
            'adapter' => 'Zfegg\Admin\DbAdapter',
            'invokable' => 'Zfegg\ModelManager\Model\DataSourceConfigTable',
        ),
        'Zfegg\ModelManager\UiConfigTable' => array(
            'table'   => 'model_manager_ui_config',
            'invokable' => 'Zfegg\ModelManager\Model\UiConfigTable',
            'primary' => 'id',
            'adapter' => 'Zfegg\Admin\DbAdapter',
        ),
    ),

    'service_manager' => array(
        'factories'  => array(
            'Zfegg\ModelManager\DataSourceManager' => 'Zfegg\ModelManager\DataSource\DataSourceManagerFactory',
        ),
    ),
);