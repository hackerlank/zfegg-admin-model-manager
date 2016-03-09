define('zfegg/admin/model-manager/source/data-source-config',
    [
        'kendo',
        'zfegg/config',
        'zfegg/kendo/restful-data-source'
    ],
    function(kendo, config, Restful) {
        'use strict';

        var restUrl = config.baseUrl + '/zfegg-admin-model-manager/data-source-configs';
        return new Restful({
            url: restUrl,
            schema: {
                model: {
                    id: "id",
                    fields: {
                        name: {editable: false},
                        adapter: {editable: false},
                        adapter_options: {},
                        fields: {}
                    }
                }
            }
        });
    });