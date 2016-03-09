define('zfegg/admin/model-manager/controller/data-source-config-add',
    [
        'require',
        'kendo',
        'zfegg/model/view',
        'zfegg/config',
        'zfegg/ui/notification'
    ],
    function (req, kendo, View, config, notification) {
        'use strict';

        var url = config.baseUrl + '/zfegg-admin-model-manager/data-source-configs';
        var testConnectionUrl = config.baseUrl + '/zfegg-admin-model-manager/test-connection';
        var testJsonRpcUrl = config.baseUrl + '/zfegg-admin-model-manager/test-json-rpc';

        var html = '';

        var initDbSelectMode = function ($elm, tables) {
            var getSelect = function (name) {
                    return $('select[name^="adapter_options[query_options][' + name + ']"]', $elm);
                },
                $selectTable = getSelect('table'),
                $selectColumn = getSelect('column'),
                $selectOrder = getSelect('order'),
                $whereSelect = $('div[class=modelManager-adapter-queryOptions][role=where]', $elm),
                $whereItem = $whereSelect.children('p').clone(),
                $viewNormalMode = $('fieldset[role="mode_normal"]', $elm),
                $sqlPreview = $('pre[role=pre_sql]', $elm)
                ;
            $whereSelect.children('p').remove();
            $whereSelect.on('click', 'button[value="+"]', function () {
                $(this).before($whereItem.clone());
            }).on('click', 'button[value="-"]', function () {
                $(this).parent().remove();
            });
            $selectTable.change(function () {
                $selectColumn.empty();
                $selectOrder.empty();
                $whereSelect.children('p').remove();
                $whereItem.find('select:eq(0)').children('option').remove();
                var table = this.value;
                $.each(tables[table], function (column, attrs) {

                    $selectColumn.append('<option value="' + column + '">' + column + '</option>');
                    $selectOrder.append('<option value="' + column + ' ASC">' + column + ' ASC</option>');
                    $selectOrder.append('<option value="' + column + ' DESC">' + column + ' DESC</option>');
                    $whereItem.find('select:eq(0)').append('<option value="' + column + '">' + column + '</option>');
                });
            });
            $.each(tables, function (table, columns) {
                $selectTable.append('<option value="' + table + '">' + table + '</option>');
            });

            $elm.on('change', 'select,input', function () {
                var sql = "SELECT ";

                if ($selectColumn.val()) {
                    sql += $selectColumn.val().join(', ');
                } else {
                    sql += ' * ';
                }
                sql += ' FROM ';
                sql += $selectTable.val();

                if ($whereSelect.find('select[name="adapter_options[query_options][where][left][]"]').size()) {
                    var conds = [];
                    $whereSelect.find('select[name="adapter_options[query_options][where][left][]"]').each(function () {
                        conds.push(this.value + ' ' + $(this).next().val() + ' ' + JSON.stringify($(this).next().next().val()));
                    });
                    if (conds.length) {
                        sql += ' WHERE ' + conds.join(' AND ');
                    }
                }

                if ($selectOrder.val()) {
                    sql += ' ORDER BY ';
                    var sorts = [];
                    $.each($selectOrder.val(), function (i, val) {
                        sorts.push(val);
                    });
                    sql += sorts.join(',');
                }

                $sqlPreview.html(sql);

                if ($selectColumn.val()) {
                    var selectColumns = {};
                    $.each($selectColumn.val(), function (i, name) {
                        selectColumns[name] = tables[$selectTable.val()][name];
                    });
                } else {
                    selectColumns = tables[$selectTable.val()];
                }
                $fieldsContainer.trigger('init', [selectColumns]);
            });
        };
        var $fieldsContainer;
        return new View(
            '个人信息',
            req.toUrl('./data-source-config-add.html'),
            {
                model: {
                    jsonRpcOptionsUrl: '',
                    testJsonRpc: function (e) {
                        $.post(
                            testJsonRpcUrl,
                            {url: this.jsonRpcOptionsUrl},
                            function (r) {
                                if (r.errors) {
                                    alert(r.errors);
                                } else {
                                    alert('连接成功');
                                    $fieldsContainer.trigger('init', [r.fields]);
                                }
                            }
                        );
                    },
                    testDbConnection: function (e) {
                        var $tmpForm = $('<form>');
                        var $view = this.$currentView;
                        $tmpForm.append($(e.target).closest('fieldset').clone());
                        $tmpForm.submit(function () {
                            $.post(
                                testConnectionUrl,
                                $tmpForm.serialize(),
                                function (r) {
                                    if (r.errors) {
                                        alert(r.errors);
                                    } else {
                                        alert('连接成功');
                                        initDbSelectMode($view, r.tables);
                                    }
                                }
                            );
                            return false;
                        }).submit();
                    },
                    onSelectAdapter: function (e) {
                        var $form = $(e.target).closest('form');
                        var $currentView = $form.find('div[data-id=' + e.target.value + ']');
                        $currentView.show().siblings('div').hide();
                        this.$currentView = $currentView;
                    },
                    onSubmit: function () {
                        $.post(this.action, $(this).serialize(), function (r) {
                            alert('添加成功');
                        });
                    },
                    $currentView: null
                },
                init: function (e) {
                    var self = this;
                    console.log(this, e);
                    var kTmplFields = kendo.template(this.element.find('#modelManager-databaseConfig-template-fields').html());

                    $fieldsContainer = this.element.find('table[name="fields-container"]');
                    $fieldsContainer.bind('init', function (e, fields) {
                        var $tbody = $(this).find('tbody');
                        $tbody.empty();
                        $tbody.append(kTmplFields({fields: fields}));
                    });
                }
            }
        );
    });