<?php
return array(
    'router' => array(
        'routes' => array(
            'model-manager' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/model-manager[/:controller[/:action]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Zfegg\\ModelManager',
                        'controller' => 'index',
                        'action' => 'index',
                    ),
                ),
                'child_routes' => array(
                    'params' => array(
                        'type' => 'Wildcard',
                    ),
                ),
            ),
            'model-manager-ui' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/ui/model-manager[/:ctrl[/:name]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Zfegg\\ModelManager',
                        'controller' => 'index',
                        'action' => 'ui',
                    ),
                ),
            ),
            'zfegg-admin-model-manager.rest.data-source-config' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/zfegg-admin-model-manager/data-source-configs[/:id]',
                    'defaults' => array(
                        'controller' => 'ZfeggAdminModelManager\\V1\\Rest\\DataSourceConfig\\Controller',
                    ),
                ),
            ),
            'zfegg-admin-model-manager.rest.ui-config' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/zfegg-admin-model-manager/ui-configs[/:id]',
                    'defaults' => array(
                        'controller' => 'ZfeggAdminModelManager\\V1\\Rest\\UiConfig\\Controller',
                    ),
                ),
            ),
            'zfegg-admin-model-manager.rpc.test-json-rpc' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/zfegg-admin-model-manager/test-json-rpc',
                    'defaults' => array(
                        'controller' => 'ZfeggAdminModelManager\\V1\\Rpc\\TestJsonRpc\\Controller',
                        'action' => 'testJsonRpc',
                    ),
                ),
            ),
            'zfegg-admin-model-manager.rpc.test-db-connection' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/zfegg-admin-model-manager/test-db-connection',
                    'defaults' => array(
                        'controller' => 'ZfeggAdminModelManager\\V1\\Rpc\\TestDbConnection\\Controller',
                        'action' => 'testDbConnection',
                    ),
                ),
            ),
        ),
    ),
    'zfc_rbac' => array(
        'guards' => array(
            'Zfegg\\Admin\\Rbac\\PermissionsGuard' => array(
                'routes' => array(
                    0 => 'model-manager*',
                ),
            ),
        ),
    ),
    'zfegg_admin' => array(
        'menus' => array(
            'model_manager' => array(
                'text' => '模型管理',
                'expanded' => true,
                'items' => array(
                    0 => array(
                        'text' => '数据表模型',
                        'index' => 0,
                        'url' => './ui/model-manager/data-source-config/index',
                    ),
                    1 => array(
                        'text' => '模型UI配置',
                        'index' => 1,
                        'url' => './ui/model-manager/ui-config/index',
                    ),
                    2 => array(
                        'text' => '模型UI列表',
                        'index' => 1,
                        'url' => './ui/model-manager/ui-config/list',
                    ),
                    3 => array(
                        'text' => '查询测试4',
                        'url' => './model-manager/source/view/id/4',
                    ),
                    4 => array(
                        'text' => '查询测试2',
                        'url' => './model-manager/source/view/id/2',
                    ),
                ),
            ),
        ),
        'permission_scan_controller_dir' => array(
            'Zfegg\\ModelManager' => 'D:\\www\\zfegg-admin-skeleton\\module\\ZfeggAdminModelManager\\config/../src/Controller',
        ),
    ),
    'zfegg_model_manager' => array(
        'data_source_manager' => array(),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            0 => 'D:\\www\\zfegg-admin-skeleton\\module\\ZfeggAdminModelManager\\config/../view',
        ),
    ),
    'db' => array(
        'adapters' => array(
            'db-zfegg-model-manager' => array(
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=zfegg-admin;host=localhost;charset=utf8;',
                'username' => 'root',
                'password' => '',
            ),
        ),
    ),
    'tables' => array(
        'ModelManager\\DataSourceConfigTable' => array(
            'table' => 'data_source_config',
            'primary' => 'id',
            'adapter' => 'Zfegg\\ModelManager',
        ),
        'ModelManager\\UiConfigTable' => array(
            'table' => 'model_manager_ui_config',
            'invokable' => 'Zfegg\\ModelManager\\Model\\UiConfigTable',
            'primary' => 'id',
            'adapter' => 'Zfegg\\ModelManager',
        ),
    ),
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                0 => 'D:\\www\\zfegg-admin-skeleton\\module\\ZfeggAdminModelManager\\config/../web/',
            ),
        ),
    ),
    'zfegg-admin' => array(
        'ui' => array(
            'modules' => array(
                0 => 'zfegg/admin/model-manager/init',
            ),
            'requirejs_config' => array(),
        ),
        'menus' => array(
            0 => array(
                'text' => '数据模型管理',
                'index' => 0,
                'expanded' => true,
                'items' => array(
                    0 => array(
                        'text' => '数据源添加',
                        'index' => 0,
                        'url' => '#/zfegg/admin/model-manager/data-source-config-add',
                    ),
                    1 => array(
                        'text' => '数据源管理',
                        'index' => 1,
                        'url' => '#/zfegg/admin/model-manager/data-source-config-list',
                    ),
                    2 => array(
                        'text' => '模型UI添加',
                        'index' => 2,
                        'url' => '#/zfegg/admin/model-manager/ui-config-add',
                    ),
                    3 => array(
                        'text' => '模型UI管理',
                        'index' => 3,
                        'url' => '#/zfegg/admin/model-manager/ui-config-list',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Zfegg\\ModelManager\\DataSourceManager' => 'Zfegg\\ModelManager\\DataSource\\DataSourceManagerFactory',
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'zfegg-admin-model-manager.rest.data-source-config',
            1 => 'zfegg-admin-model-manager.rest.ui-config',
            2 => 'zfegg-admin-model-manager.rpc.test-json-rpc',
            3 => 'zfegg-admin-model-manager.rpc.test-db-connection',
        ),
    ),
    'zf-rest' => array(
        'ZfeggAdminModelManager\\V1\\Rest\\DataSourceConfig\\Controller' => array(
            'listener' => 'ZfeggAdminModelManager\\V1\\Rest\\DataSourceConfig\\DataSourceConfigResource',
            'route_name' => 'zfegg-admin-model-manager.rest.data-source-config',
            'route_identifier_name' => 'id',
            'collection_name' => 'items',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'ZfeggAdminModelManager\\V1\\Rest\\DataSourceConfig\\DataSourceConfigEntity',
            'collection_class' => 'ZfeggAdminModelManager\\V1\\Rest\\DataSourceConfig\\DataSourceConfigCollection',
            'service_name' => 'DataSourceConfig',
        ),
        'ZfeggAdminModelManager\\V1\\Rest\\UiConfig\\Controller' => array(
            'listener' => 'ZfeggAdminModelManager\\V1\\Rest\\UiConfig\\UiConfigResource',
            'route_name' => 'zfegg-admin-model-manager.rest.ui-config',
            'route_identifier_name' => 'id',
            'collection_name' => 'items',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'ZfeggAdminModelManager\\V1\\Rest\\UiConfig\\UiConfigEntity',
            'collection_class' => 'ZfeggAdminModelManager\\V1\\Rest\\UiConfig\\UiConfigCollection',
            'service_name' => 'UiConfig',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'ZfeggAdminModelManager\\V1\\Rest\\DataSourceConfig\\Controller' => 'HalJson',
            'ZfeggAdminModelManager\\V1\\Rest\\UiConfig\\Controller' => 'HalJson',
            'ZfeggAdminModelManager\\V1\\Rpc\\TestJsonRpc\\Controller' => 'Json',
            'ZfeggAdminModelManager\\V1\\Rpc\\TestDbConnection\\Controller' => 'Json',
        ),
        'accept_whitelist' => array(
            'ZfeggAdminModelManager\\V1\\Rest\\DataSourceConfig\\Controller' => array(
                0 => 'application/vnd.zfegg-admin-model-manager.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'ZfeggAdminModelManager\\V1\\Rest\\UiConfig\\Controller' => array(
                0 => 'application/vnd.zfegg-admin-model-manager.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'ZfeggAdminModelManager\\V1\\Rpc\\TestJsonRpc\\Controller' => array(
                0 => 'application/json',
                1 => 'application/*+json',
            ),
            'ZfeggAdminModelManager\\V1\\Rpc\\TestDbConnection\\Controller' => array(
                0 => 'application/vnd.zfegg-admin-model-manager.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
        ),
        'content_type_whitelist' => array(
            'ZfeggAdminModelManager\\V1\\Rest\\DataSourceConfig\\Controller' => array(
                0 => 'application/vnd.zfegg-admin-model-manager.v1+json',
                1 => 'application/json',
            ),
            'ZfeggAdminModelManager\\V1\\Rest\\UiConfig\\Controller' => array(
                0 => 'application/vnd.zfegg-admin-model-manager.v1+json',
                1 => 'application/json',
            ),
            'ZfeggAdminModelManager\\V1\\Rpc\\TestJsonRpc\\Controller' => array(
                0 => 'application/json',
                1 => 'application/x-www-form-urlencoded',
            ),
            'ZfeggAdminModelManager\\V1\\Rpc\\TestDbConnection\\Controller' => array(
                0 => 'application/vnd.zfegg-admin-model-manager.v1+json',
                1 => 'application/json',
                2 => 'application/x-www-form-urlencoded',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'ZfeggAdminModelManager\\V1\\Rest\\DataSourceConfig\\DataSourceConfigEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'zfegg-admin-model-manager.rest.data-source-config',
                'route_identifier_name' => 'id',
                'hydrator' => 'Zend\\Hydrator\\ArraySerializable',
            ),
            'ZfeggAdminModelManager\\V1\\Rest\\DataSourceConfig\\DataSourceConfigCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'zfegg-admin-model-manager.rest.data-source-config',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ),
            'ZfeggAdminModelManager\\V1\\Rest\\UiConfig\\UiConfigEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'zfegg-admin-model-manager.rest.ui-config',
                'route_identifier_name' => 'id',
                'hydrator' => 'Zend\\Hydrator\\ArraySerializable',
            ),
            'ZfeggAdminModelManager\\V1\\Rest\\UiConfig\\UiConfigCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'zfegg-admin-model-manager.rest.ui-config',
                'route_identifier_name' => 'id',
                'is_collection' => true,
            ),
        ),
    ),
    'zf-apigility' => array(
        'db-connected' => array(
            'ZfeggAdminModelManager\\V1\\Rest\\DataSourceConfig\\DataSourceConfigResource' => array(
                'adapter_name' => 'db-zfegg-model-manager',
                'table_name' => 'model_manager_data_source_config',
                'hydrator_name' => 'Zend\\Hydrator\\ArraySerializable',
                'controller_service_name' => 'ZfeggAdminModelManager\\V1\\Rest\\DataSourceConfig\\Controller',
                'entity_identifier_name' => 'id',
                'table_service' => 'ZfeggAdminModelManager\\V1\\Rest\\DataSourceConfig\\DataSourceConfigResource\\Table',
            ),
            'ZfeggAdminModelManager\\V1\\Rest\\UiConfig\\UiConfigResource' => array(
                'adapter_name' => 'db-zfegg-model-manager',
                'table_name' => 'model_manager_ui_config',
                'hydrator_name' => 'Zend\\Hydrator\\ArraySerializable',
                'controller_service_name' => 'ZfeggAdminModelManager\\V1\\Rest\\UiConfig\\Controller',
                'entity_identifier_name' => 'id',
                'table_service' => 'ZfeggAdminModelManager\\V1\\Rest\\UiConfig\\UiConfigResource\\Table',
            ),
        ),
    ),
    'zf-content-validation' => array(
        'ZfeggAdminModelManager\\V1\\Rest\\DataSourceConfig\\Controller' => array(
            'input_filter' => 'ZfeggAdminModelManager\\V1\\Rest\\DataSourceConfig\\Validator',
        ),
        'ZfeggAdminModelManager\\V1\\Rpc\\TestJsonRpc\\Controller' => array(
            'input_filter' => 'ZfeggAdminModelManager\\V1\\Rpc\\TestJsonRpc\\Validator',
        ),
    ),
    'input_filter_specs' => array(
        'ZfeggAdminModelManager\\V1\\Rest\\DataSourceConfig\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\Alpha',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'name',
            ),
            1 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\InArray',
                        'options' => array(
                            'haystack' => array(
                                0 => 'DbAdapter',
                                1 => 'JsonRpc',
                            ),
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'adapter',
            ),
            2 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'adapter_options',
            ),
            3 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'fields',
            ),
        ),
        'ZfeggAdminModelManager\\V1\\Rpc\\TestJsonRpc\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Uri',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'url',
            ),
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'ZfeggAdminModelManager\\V1\\Rpc\\TestJsonRpc\\Controller' => 'ZfeggAdminModelManager\\V1\\Rpc\\TestJsonRpc\\TestJsonRpcControllerFactory',
            'ZfeggAdminModelManager\\V1\\Rpc\\TestDbConnection\\Controller' => 'ZfeggAdminModelManager\\V1\\Rpc\\TestDbConnection\\TestDbConnectionControllerFactory',
        ),
    ),
    'zf-rpc' => array(
        'ZfeggAdminModelManager\\V1\\Rpc\\TestJsonRpc\\Controller' => array(
            'service_name' => 'TestJsonRpc',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'zfegg-admin-model-manager.rpc.test-json-rpc',
        ),
        'ZfeggAdminModelManager\\V1\\Rpc\\TestDbConnection\\Controller' => array(
            'service_name' => 'TestDbConnection',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'zfegg-admin-model-manager.rpc.test-db-connection',
        ),
    ),
);
