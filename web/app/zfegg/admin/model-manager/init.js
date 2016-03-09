define('zfegg/admin/model-manager/init', [
    'zfegg/router',
    'zfegg/ui/tabs'
], function (router, $tabs) {
    'use strict';

    router.route('/zfegg/admin/model-manager/:action', function (action) {
        $tabs.dispatchController('zfegg/admin/model-manager/controller/' + action);
    });
});