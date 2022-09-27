<?php
Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {

    Route::group(['prefix' => 'search', 'namespace' => 'Search'], function () {

        Route::get('/', 'SearchController@index');

    });

    Route::group(['prefix' => 'analitics', 'namespace' => 'Analitics'], function () {

        Route::group(['prefix' => 'total', 'namespace' => 'Total'], function () {

            Route::get('/', 'TotalAnalyticsController@index');

            Route::post('/get_filters', 'TotalAnalyticsController@get_filters');

            Route::post('/get_charts', 'TotalAnalyticsController@get_charts');

        });

        Route::group(['prefix' => 'sales', 'namespace' => 'Sales'], function () {

            Route::get('/', 'AnalyticsSalesController@index');

            Route::post('/get_payments_table', 'AnalyticsSalesController@get_payments_table');

            Route::post('/get_payments_table_to_excel', 'AnalyticsSalesController@get_payments_table_to_excel');

        });

    });


    Route::group(['prefix' => 'payment/{id}', 'namespace' => 'Payment'], function () {

        Route::get('/', 'PaymentController@index');

        Route::post('/', 'PaymentController@save');

        Route::post('/detach_receipt', 'PaymentController@detach_receipt');

        Route::get('/delete', 'PaymentController@delete');

    });

    Route::group(['prefix' => 'cashbox', 'namespace' => 'Cashbox'], function () {

        Route::group(['prefix' => 'invoice', 'namespace' => 'Invoice'], function () {

            Route::get('/', 'CashboxInvoiceController@index');

            Route::post('/get_invoices_table', 'CashboxInvoiceController@get_invoices_table');

            Route::get('{id}/edit', 'CashboxInvoiceController@edit')->where('id', '[0-9]+');

            Route::get('{id}/view', 'CashboxInvoiceController@view')->where('id', '[0-9]+');

            Route::post('{id}/data_invoice_payment', 'CashboxInvoiceController@data_invoice_payment')->where('id', '[0-9]+');

            Route::post('{id}/save', 'CashboxInvoiceController@save')->where('id', '[0-9]+');

        });

        Route::group(['prefix' => 'payment_reports', 'namespace' => 'PaymentReports'], function () {

            Route::get('/', 'PaymentReportsController@index');

            Route::post('/table', 'PaymentReportsController@reports_table');

        });

    });

    Route::group(['prefix' => 'bso', 'namespace' => 'BSO'], function () {

        Route::get('/add_bso_warehouse', 'AddBsoWarehouseController@index');

        Route::post('/add_bso_warehouse/add_bso', 'AddBsoWarehouseController@add_bso');

        Route::group(['prefix' => 'transfer', 'namespace' => 'Transfer'], function () {

            Route::get('/', 'TransferBsoController@index');

            Route::get('/get_user_ban_reason/', 'TransferBsoController@get_user_ban_reason');

            Route::get('/get_agent_info/', 'TransferBsoController@get_agent_info');

            Route::get('/create_bso_cart/', 'TransferBsoController@create_bso_cart');

            Route::get('/bso_cart_content/', 'TransferBsoController@bso_cart_content');

            Route::get('/get_bso_types/', 'TransferBsoController@get_bso_types');

            Route::get('/bso_selector/', 'TransferBsoController@bso_selector');

            Route::get('/get_bsos/', 'TransferBsoController@get_bsos');

            Route::get('/get_all_bsos/', 'TransferBsoController@get_all_bsos');

            Route::get('/move_to_cart/', 'TransferBsoController@move_to_cart');

            Route::get('/remove_from_bso_cart/', 'TransferBsoController@remove_from_bso_cart');

            Route::get('/remove_cart/', 'TransferBsoController@remove_cart');

            Route::get('/selector_bso_transfer/', 'TransferBsoController@selector_bso_transfer');

            Route::get('reserve_export', 'TransferBsoController@reserve_export');

            Route::get('/transfer_bso/', 'TransferFinishBsoController@transfer_bso');

            Route::get('/transfer_bso_act_agent/', 'TransferFinishBsoController@transfer_bso_act_agent');

        });

        Route::group(['prefix' => 'inventory_agents'], function () {

            Route::get('/', 'InventoryAgentsController@index');

            Route::post('/get_agents_table', 'InventoryAgentsController@get_agents_table');

            Route::get('/export', 'InventoryAgentsController@inventory_bso_export');

            Route::get('/details', 'InventoryAgentsController@details');

            Route::post('/details_list', 'InventoryAgentsController@details_list');

            Route::post('/get_details_table', 'InventoryAgentsController@get_details_table');

            Route::get('/details_export', 'InventoryAgentsController@details_export');

        });

        Route::group(['prefix' => 'inventory_bso'], function () {

            Route::get('/', 'InventoryBsoController@index');

            Route::get('/export', 'InventoryBsoController@inventory_bso_export');

            Route::get('/details', 'InventoryBsoController@details');

            Route::post('/details_list', 'InventoryBsoController@details_list');

            Route::post('/get_details_table', 'InventoryBsoController@get_details_table');

            Route::get('/details_export', 'InventoryBsoController@details_export');

        });

        Route::group(['prefix' => 'actions'], function () {

            Route::get('/get_bso_type', 'ActionsController@get_bso_type');

            Route::get('/get_series', 'ActionsController@get_series');
            Route::get('/get_dop_series', 'ActionsController@get_dop_series');
            Route::get('/bso_number_to', 'ActionsController@bso_number_to');
            Route::get('/create_transfer_act', 'ActionsController@create_transfer_act');

            Route::post('/get_bso/suggest/party/', 'ActionsController@get_bso');
            Route::post('/get_bso_sold/suggest/party/', 'ActionsController@get_bso_sold');
            Route::post('/get_bso_order/suggest/party/', 'ActionsController@get_bso_order');
            Route::post('/get_clear_bso/suggest/party/', 'ActionsController@get_clear_bso');
            Route::post('/get_bso_contracts/suggest/party/', 'ActionsController@get_bso_contracts');

            Route::get('/get_bso/status/party/', 'ActionsController@status_party');
            Route::get('/get_bso_sold/status/party/', 'ActionsController@status_party');
            Route::get('/get_bso_order/status/party/', 'ActionsController@status_party');
            Route::get('/get_clear_bso/status/party/', 'ActionsController@status_party');
            Route::get('/get_bso_contracts/status/party/', 'ActionsController@status_party');

            Route::get('/get_bso/iplocate/address/', 'ActionsController@status_party');
            Route::get('/get_bso_sold/iplocate/address/', 'ActionsController@status_party');
            Route::get('/get_bso_order/iplocate/address/', 'ActionsController@status_party');
            Route::get('/get_clear_bso/iplocate/address/', 'ActionsController@status_party');
            Route::get('/get_bso_contracts/iplocate/address/', 'ActionsController@status_party');

            Route::get('/get_bso/detectAddressByIp/', 'ActionsController@status_party');
            Route::get('/get_bso_sold/detectAddressByIp/', 'ActionsController@status_party');
            Route::get('/get_bso_order/detectAddressByIp/', 'ActionsController@status_party');
            Route::get('/get_clear_bso/detectAddressByIp/', 'ActionsController@status_party');
            Route::get('/get_bso_contracts/detectAddressByIp/', 'ActionsController@status_party');


            Route::get('/get_installment_algorithms', 'ActionsController@get_installment_algorithms');
            Route::get('/get_financial_policy', 'ActionsController@get_financial_policy');

            Route::get('/get_html_mini_contract_object_insurer', 'ActionsController@get_html_mini_contract_object_insurer');

            Route::post('/get_orders_front/suggest/party/', 'ActionsController@get_orders_front');

            Route::get('/get_order_id_front', 'ActionsController@get_order_id_front');

        });

        Route::group(['prefix' => '/items', 'namespace' => 'Items'], function () {

            Route::get('{id}/', 'BsoItemsController@index');

            Route::get('{id}/edit_supplier_org', 'BsoItemsController@supplier_org');
            Route::post('{id}/edit_supplier_org', 'BsoItemsController@edit_supplier_org');

            Route::get('{id}/edit_bso_title', 'BsoItemsController@bso_title');
            Route::post('{id}/edit_bso_title', 'BsoItemsController@edit_bso_title');

            Route::get('{id}/edit_bso_state', 'BsoItemsController@bso_state');
            Route::post('{id}/edit_bso_state', 'BsoItemsController@edit_bso_state');

        });

    });

    Route::group(['prefix' => 'reports', 'namespace' => 'Reports'], function () {

        Route::group(['prefix' => 'reports_sk', 'namespace' => 'ReportsSK'], function () {

            Route::get('/', 'ReportsSKController@index');

            Route::group(['prefix' => '/{organization_id}', 'where' => ['org_id' => '[0-9]+']], function () {

                Route::get('/info', 'ReportsSKController@reports');
                Route::post('/info/table', 'ReportsSKController@reports_table');

                Route::group(['prefix' => '/bordereau'], function () {

                    Route::get('/', "ReportsSKFormationController@index");

                    Route::post('/get_table', "ReportsSKFormationController@get_table");

                    Route::post('/get_action_table', "ReportsSKFormationController@get_action_table");

                    Route::post('/execute', "ReportsSKFormationController@execute");

                });

                Route::group(['prefix' => '/dvoy'], function () {

                    Route::get('/', "ReportsSKFormationController@index");

                    Route::post('/get_table', "ReportsSKFormationController@get_table");

                    Route::post('/get_action_table', "ReportsSKFormationController@get_action_table");

                    Route::post('/execute', "ReportsSKFormationController@execute");

                });

            });

        });

        Route::group(['prefix' => '/order/{report_id}', 'namespace' => 'ReportsSK', 'where' => ['report_id' => '[0-9]+']], function () {

            Route::get('/', 'ReportsSKOrderController@index');
            Route::post('/', 'ReportsSKOrderController@save');

            Route::post('/set_status', 'ReportsSKOrderController@set_status');

            Route::post('/recalc_kv', 'ReportsSKOrderController@recalc_kv');

            Route::post('/delete_payments', 'ReportsSKOrderController@delete_payments');

            Route::post('/delete_order', 'ReportsSKOrderController@delete_order');

            Route::post('/delete_report_with_payments', 'ReportsSKOrderController@delete_report_with_payments');

            Route::post('/marker_payments', 'ReportsSKOrderController@marker_payments');

            Route::get('/form_report', 'ReportsSKOrderController@form_report');

            Route::group(['prefix' => 'payment_sum', 'namespace' => 'PaymentSum'], function () {

                Route::get('create', 'ReportPaymentSumController@create');

                Route::get('{payment_sum_id}/edit', 'ReportPaymentSumController@edit');

                Route::post('store', 'ReportPaymentSumController@store');

                Route::post('{payment_sum_id}/save', 'ReportPaymentSumController@save');

                Route::post('{payment_sum_id}/delete', 'ReportPaymentSumController@delete');

            });

        });

    });

    Route::group(['prefix' => 'bso_acts', 'namespace' => 'BsoActs'], function () {

        Route::get('show_bso_act/{id}', 'ShowBsoActController@index');

        Route::post('update/{id}', 'ShowBsoActController@update');

        Route::get('export/{id}', 'ShowBsoActController@export');

        Route::post('export_bso_act/{id}', 'ShowBsoActController@export_bso_act');

        Route::group(['prefix' => 'acts_sk', 'namespace' => 'ActsSK'], function () {

            Route::get('/', 'ActsSKController@index');

            Route::post('get_filters', 'ActsSKController@get_filters');

            Route::post('get_table', 'ActsSKController@get_table');

            Route::group(['prefix' => '{supplier_id}', 'where' => ['supplier_id' => '[0-9]+']], function () {

                Route::post('acts_list_table', "ActsSKController@acts_list_table");

                Route::group(['prefix' => 'acts'], function () {

                    Route::get('/', "ActsSKController@acts");

                    Route::group(['prefix' => '{act_id}', 'where' => ['act_id' => '[0-9]+']], function () {

                        Route::get('edit', "ActsSKController@edit");

                        Route::get('export', "ActsSKController@export");

                        Route::post('accept', "ActsSKController@accept");

                        Route::post('act_files', "ActsSKController@act_files");

                        Route::post('update', "ActsSKController@update");

                        Route::post('delete_items', "ActsSKController@delete_items");

                        Route::post('delete_bsos', "ActsSKController@delete_bsos");

                        Route::post('delete_payments', "ActsSKController@delete_payments");

                    });

                });

                Route::group(['prefix' => '/bso', 'namespace' => 'Bso'], function () {

                    Route::get('/', "BsoActsController@index");

                    Route::post('get_table', "BsoActsController@get_table");

                    Route::post('get_action_table', "BsoActsController@get_action_table");

                    Route::post('execute_bso', "BsoActsController@execute_bso");

                });

                Route::group(['prefix' => 'contracts', 'namespace' => 'Contracts'], function () {

                    Route::get('/', "ContractActsController@index");

                    Route::post('get_table', "ContractActsController@get_table");

                    Route::post('get_action_table', "ContractActsController@get_action_table");

                    Route::post('execute_payments', "ContractActsController@execute_payments");

                });

            });

        });

        Route::group(['prefix' => 'acts_transfer', 'namespace' => 'ActsTransfer'], function () {

            Route::get('/', 'ActsTransferController@index');

            Route::post('/get_acts_table', 'ActsTransferController@get_acts_table');

        });

        Route::group(['prefix' => 'acts_reserve', 'namespace' => 'ActsReserve'], function () {

            Route::get('/', 'ActsReserveController@index');

            Route::post('/get_acts_table', 'ActsReserveController@get_acts_table');

        });

        Route::group(['prefix' => 'acts_implemented', 'namespace' => 'ActsImplemented'], function () {

            Route::get('/', 'ActsImplementedController@index');

            Route::get('/get_view', 'ActsImplementedController@get_view');

            Route::post('/acts/list', 'ActsImplementedController@acts_list');

            Route::post('/get_realized_acts', 'ActsImplementedController@get_realized_acts');

            Route::post('/contract/list', 'ActsImplementedController@contract_list');
            Route::post('/contract/create_get_realized_acts', 'ActsImplementedController@create_get_realized_acts');

            Route::post('/spoiled/list', 'ActsImplementedController@spoiled_list');

            Route::get('/spoiled/edit', 'ActsImplementedController@edit_spoiled');
            Route::post('/spoiled/edit', 'ActsImplementedController@save_spoiled');

            Route::post('/spoiled/create_get_realized_acts', 'ActsImplementedController@create_spoiled_realized_acts');

            Route::post('/сlean/list', 'ActsImplementedController@сlean_list');
            Route::post('/сlean/create_get_realized_acts', 'ActsImplementedController@create_сlean_realized_acts');

            Route::group(['prefix' => 'details/{act_id}', 'namespace' => 'Details'], function () {

                Route::get('/', 'ActsImplementedDetailsController@index');

                Route::get('/export', 'ActsImplementedDetailsController@export');

                Route::post('/delete_items', 'ActsImplementedDetailsController@delete_items');

                Route::post('/delete_act', 'ActsImplementedDetailsController@delete_act');

                Route::post('/accept', 'ActsImplementedDetailsController@accept');

            });

        });

        Route::group(['prefix' => 'acts_transfer_tp', 'namespace' => 'ActsTransferTP'], function () {

            Route::get('/', 'ActsTransferTPController@index');

            Route::get('/get_view', 'ActsTransferTPController@get_view');

            Route::post('/acts/list', 'ActsTransferTPController@acts_list');

            Route::post('/get_realized_acts', 'ActsTransferTPController@get_realized_acts');

            Route::post('/contract/list', 'ActsTransferTPController@contract_list');
            Route::post('/contract/create_get_realized_acts', 'ActsTransferTPController@create_get_realized_acts');

            Route::post('/spoiled/list', 'ActsTransferTPController@spoiled_list');

            Route::get('/spoiled/edit', 'ActsTransferTPController@edit_spoiled');
            Route::post('/spoiled/edit', 'ActsTransferTPController@save_spoiled');

            Route::post('/spoiled/create_get_realized_acts', 'ActsTransferTPController@create_spoiled_realized_acts');

            Route::post('/сlean/list', 'ActsTransferTPController@сlean_list');
            Route::post('/сlean/create_get_realized_acts', 'ActsTransferTPController@create_сlean_realized_acts');

            Route::group(['prefix' => 'details/{act_id}', 'namespace' => 'Details'], function () {

                Route::get('/', 'ActsTransferTPDetailsController@index');

                Route::get('/export', 'ActsTransferTPDetailsController@export');

                Route::post('/delete_items', 'ActsTransferTPDetailsController@delete_items');

                Route::post('/delete_act', 'ActsTransferTPDetailsController@delete_act');

                Route::post('/accept', 'ActsTransferTPDetailsController@accept');

            });

        });

    });

    Route::group(['prefix' => 'contracts', 'namespace' => 'Contracts'], function () {

        Route::group(['prefix' => 'online', 'namespace' => 'Online'], function () {

            Route::get('/', 'OnlineController@index');
            Route::post('/', 'OnlineController@getDraftTable');
            Route::post('/draft-delete', 'OnlineController@deleteDraft');


            Route::get('/{id}', 'OnlineController@edit');

            Route::get('/{product_id}/create', 'OnlineController@create');

            Route::post('/{id}/save', 'OnlineController@save');
            Route::post('/{id}/calc', 'OnlineController@calc');
            Route::get('/{id}/calculation', 'OnlineController@setCalculation');

            Route::post('/{id}/refresh-mask', 'OnlineController@refreshMask');

            Route::post('/{id}/copy', 'OnlineController@copy');
            Route::post('/{id}/prolongation', 'OnlineController@prolongation');

            Route::post('/{id}/edit-status', 'OnlineController@editStatus');

            Route::get('/{id}/cancel', 'OnlineController@cancel_contract');
            Route::post('/{id}/cancel', 'OnlineController@delete_contract');

            Route::get('/{id}/release', 'OnlineController@release');
            Route::post('/{id}/accept', 'OnlineController@accept');

            Route::get('/{id}/payment/{payment_id}', 'OnlineController@payment');
            Route::post('/{id}/payment/{payment_id}', 'OnlineController@payment_accept');
            Route::post('/{id}/payment/{payment_id}/check-status', 'OnlineController@payment_check_status');

            Route::get('/{id}/action/subject', 'ActionController@subject');
            Route::get('/{id}/action/clone-general', 'ActionController@clone_general');
            Route::get('/{id}/action/clear-general', 'ActionController@clear_general');
            Route::post('/{id}/action/view-control', 'ActionController@get_control_view');

            Route::get('/{id}/action/get-document-general', 'ActionController@get_document_general');

            Route::get('/{id}/action/subject/search/ul', 'ActionController@searchUL');
            Route::post('/{id}/action/subject/search/ul', 'ActionController@searchUL');

            Route::get('/{id}/action/get-form-html', 'ActionController@getFormHtml');

            Route::get('/{id}/action/history', 'ActionController@getHistory');

            Route::get('/{id}/action/print', 'ActionController@getPrintList');

            Route::get('/{id}/action/send-matching', 'ActionController@getSendMatching');
            Route::post('/{id}/action/send-matching', 'ActionController@setSendMatching');

            Route::group(['prefix' => '/{id}/action/product'], function () {

                Route::get('/procedures/list/{general_subject_id}', 'ActionProductController@getProcedures');

                Route::get('/procedures/{procedure_id}', 'ActionProductController@procedures');
                Route::post('/procedures/{procedure_id}', 'ActionProductController@saveProcedures');
                Route::delete('/procedures/{procedure_id}', 'ActionProductController@deleteProcedures');

                Route::get('/documents/{doc_id}/status/{status_id}', 'ActionProductController@saveStatusDocuments');
                Route::post('/documents/{key}', 'ActionProductController@saveDocuments');

                Route::get('/auto/{category}/mark/', 'ActionProductController@getAutoMark');
                Route::get('/auto/{category}/models/{mark}', 'ActionProductController@getAutoModels');
                Route::get('/auto/{category}/models-classification/{model}', 'ActionProductController@getAutoModelsClassification');

            });

            Route::group(['prefix' => '/{id}/load'], function () {

                Route::group(['prefix' => '/xls'], function () {

                    Route::get('/vzr', 'LoadXLSController@vzr');
                    Route::post('/vzr', 'LoadXLSController@vzr_set_data');

                    Route::get('/prf', 'LoadXLSController@prf');
                    Route::post('/prf', 'LoadXLSController@prf_set_data');

                    Route::post('/data-file', 'LoadXLSController@get_file');

                });

            });

            Route::group(['prefix' => '/{id}/matching/'], function () {

                Route::get('/send', 'MatchingController@create');

                Route::get('/supplementary/{number}', 'MatchingController@createSupplementary');

            });

            Route::group(['prefix' => '/{id}/supplementary/'], function () {

                Route::get('/create', 'SupplementaryController@create');

                Route::get('/{number}', 'SupplementaryController@edit');

                Route::post('/{number}/save', 'SupplementaryController@save');

                Route::delete('/{number}', 'SupplementaryController@delete');

                Route::get('/{number}/set-edit', 'SupplementaryController@setEdit');

                Route::get('/{number}/release', 'SupplementaryController@release');

            });

        });

        Route::group(['prefix' => 'actions'], function () {

            Route::post('/chat/{contract_id}/push/{type}/', 'ActionsChatController@setPush');
            Route::get('/chat/{contract_id}/read/{type}/', 'ActionsChatController@read');
            Route::get('/chat/{contract_id}/documents/{type}/', 'ActionsChatController@documents');

            Route::post('/chat/{contract_id}/notes/{type}/', 'ActionsChatController@setNotes');

            Route::post('/chat/{contract_id}/documents/{type}/load/', 'ActionsChatController@setDocuments');

            Route::resource('/{contract_id}/scans', 'ActionsScansController');
            Route::post('/{contract_id}/document', 'ActionsScansController@document');

            Route::post('/{contract_id}/document/{document_id}', 'ActionsScansController@addDocument');
            Route::delete('/{contract_id}/document/{document_id}', 'ActionsScansController@deleteDocument');

        });

        Route::group(['prefix' => 'search', 'namespace' => 'Search'], function () {

            Route::get('/', 'SearchController@index');

            Route::post('/get_payments_table', 'SearchController@get_payments_table');
            Route::post('/get_payments_table_to_excel', 'SearchController@get_payments_table_to_excel');

        });

        Route::group(['prefix' => 'prolongation', 'namespace' => 'Prolongation'], function () {

            Route::get('/', 'ProlongationController@index');

            Route::post('/table', 'ProlongationController@table');

        });
    });

    Route::group(['prefix' => 'matching', 'namespace' => 'Matching'], function () {

        Route::get('/scoring/{id}', 'MatchingController@scoring');

        Route::group(['prefix' => 'underwriting', 'namespace' => 'Underwriting'], function () {

            Route::get('/', 'MatchingUnderwritingController@index');

            Route::post('/get-table', 'MatchingUnderwritingController@getTable');

            Route::group(['prefix' => '/{id}'], function () {

                Route::get('/', 'MatchingUnderwritingController@edit');

                Route::get('/log', 'MatchingUnderwritingController@logCheckUser');

                Route::get('/refresh-scoring', 'MatchingUnderwritingController@refreshScoring');

                Route::post('/set-status', 'MatchingUnderwritingController@setStatus');

                Route::post('/set-check-user', 'MatchingUnderwritingController@setCheckUser');

                Route::post('/clear-check-user', 'MatchingUnderwritingController@clearCheckUser');

                Route::post('/set-tariff', 'MatchingUnderwritingController@setTariff');



            });

        });

        Route::group(['prefix' => 'security-service', 'namespace' => 'SecurityService'], function () {

            Route::get('/', 'MatchingSecurityServiceController@index');

            Route::post('/get-table', 'MatchingSecurityServiceController@getTable');

            Route::group(['prefix' => '/{id}'], function () {

                Route::get('/', 'MatchingSecurityServiceController@edit');

                Route::get('/log', 'MatchingSecurityServiceController@logCheckUser');

                Route::post('/set-status', 'MatchingSecurityServiceController@setStatus');

                Route::post('/set-check-user', 'MatchingSecurityServiceController@setCheckUser');

                Route::post('/clear-check-user', 'MatchingSecurityServiceController@clearCheckUser');

            });

        });

        Route::group(['prefix' => 'archive', 'namespace' => 'Archive'], function () {

            Route::get('/', 'MatchingArchiveController@index');

            Route::post('/table', 'MatchingArchiveController@table');

        });

    });

    Route::group(['prefix' => 'orders', 'namespace' => 'Orders'], function () {

        Route::post('/actions/get_point_sale', 'ActionsController@get_point_sale');
        Route::post('/actions/{order_id}/scan_damages', 'ActionsController@scan_damages');
        Route::post('/actions/{order_id}/comment_pso', 'ActionsController@comment_pso');

        Route::post('/actions/chat/{order_id}/push', 'ActionsController@setPush');
        Route::get('/actions/chat/{order_id}/read', 'ActionsController@read');
        Route::get('/actions/chat/{order_id}/documents', 'ActionsController@documents');

        Route::post('/actions/{order_id}/set-pso', 'ActionsController@setPso');

        Route::group(['prefix' => 'pso', 'namespace' => 'PSO'], function () {

            Route::get('/', 'PsoOrdersController@index');

            Route::post('/list', 'PsoOrdersController@list_view');

            Route::get('/people-list', 'PsoOrdersController@people_list');

            Route::get('/assign-user', 'PsoOrdersController@assign_user');

            Route::get('/{id}', 'PsoOrdersController@edit');

            Route::get('/{id}/get-html-block', 'PsoOrdersController@get_html_block');

            Route::post('/{id}/work-status', 'PsoOrdersController@work_status');

        });

        Route::group(['prefix' => 'damages', 'namespace' => 'Damages'], function () {

            Route::get('/', 'DamagesOrdersController@index');

            Route::post('/list', 'DamagesOrdersController@list_view');

            Route::get('/people-list', 'DamagesOrdersController@people_list');

            Route::get('/assign-user', 'DamagesOrdersController@assign_user');

            Route::get('/create', 'DamagesOrdersController@create');
            Route::post('/create', 'DamagesOrdersController@store');

            Route::get('/{id}', 'DamagesOrdersController@edit');
            Route::post('/{id}/save', 'DamagesOrdersController@save');
            Route::post('/{id}/save-status-payment', 'DamagesOrdersController@save_status_payment');
            Route::post('/{id}/work-status', 'DamagesOrdersController@work_status');

            Route::get('/{id}/payment/{payment_id}', 'DamagesOrdersController@payment_edit');
            Route::post('/{id}/payment/{payment_id}', 'DamagesOrdersController@payment_save');
            Route::delete('/{id}/payment/{payment_id}', 'DamagesOrdersController@payment_delete');

            Route::get('/{id}/get-html-block', 'DamagesOrdersController@get_html_block');

        });

    });

    Route::group(['prefix' => 'settings', 'namespace' => 'Settings'], function () {

        Route::resource('financial_policy', 'FinancialPolicyController');

        Route::resource('payment_methods', 'PaymentMethodsController');

        Route::group(['prefix' => 'installment_algorithms_payment'], function () {

            Route::get('/', 'InstallmentAlgorithmsPaymentController@index');

            Route::get('/{id}/edit', 'InstallmentAlgorithmsPaymentController@edit');

            Route::post('/{id}/edit', 'InstallmentAlgorithmsPaymentController@save');

            Route::delete('/{id}/delete', 'InstallmentAlgorithmsPaymentController@destroy');

        });

        Route::resource('banks', 'BanksController');

        Route::resource('currency', 'CurrencyController');

        Route::resource('country', 'CountryController');

        Route::resource('incomes_expenses_categories', 'IncomeExpenseCategoryController');

        Route::post('incomes_expenses_cetegories_table', 'IncomeExpenseCategoryController@get_table');

        Route::resource('departments', 'DepartmentsController');

        Route::post('departments_table', 'DepartmentsController@get_table');

        Route::resource('filials', 'FilialsController');

        Route::resource('type_org', 'TypeOrgController'); // ТИП Организация

        Route::resource('citys', 'CityController'); // Города

        Route::resource('points_sale', 'PointsSaleController'); // Точки продаж

        Route::resource('salaries_states', 'SalariesStatesController');

        Route::resource('user_balance', 'UserBalanceController');

        Route::resource('templates', 'TemplatesController');
        Route::post('/templates/get_table/', 'TemplatesController@get_table');

        Route::get('/system', 'SettingsSystemController@index');
        Route::post('/system', 'SettingsSystemController@save');

        Route::group(['prefix' => 'system'], function () {

            Route::get('/integration', 'SettingsSystemController@addIntegration');
            Route::post('/integration', 'SettingsSystemController@addIntegration');

            Route::get('/integration/{id}', 'SettingsSystemController@editIntegration');
            Route::post('/integration/{id}', 'SettingsSystemController@editIntegration');
            Route::delete('/integration/{id}', 'SettingsSystemController@deleteIntegration');

            Route::get('/integration/{integration_id}/edit/{id}', 'SettingsSystemController@editVersion');
            Route::post('/integration/{integration_id}/edit/{id}', 'SettingsSystemController@editVersion');

            Route::get('/integration/{integration_id}/add_version/', 'SettingsSystemController@addVersion');
            Route::post('/integration/{integration_id}/add_version/', 'SettingsSystemController@addVersion');

            Route::delete('/integration/{integration_id}/delete/{id}', 'SettingsSystemController@deleteVersion');
            Route::get('/integration/{integration_id}/edit/{id}/main_form', 'SettingsSystemController@versionMainForm');
            Route::post('/integration/{integration_id}/edit/{id}/main_form', 'SettingsSystemController@versionMainForm');

        });

    });

    Route::group(['prefix' => 'log', 'namespace' => 'Log'], function () {

        Route::get('/events', 'LogEventsController@index');

    });

    Route::group(['prefix' => 'directories', 'namespace' => 'Directories'], function () {

        Route::group(['prefix' => 'organizations', 'namespace' => 'Organizations'], function () {

            Route::post('{id}/delete', 'OrganizationsController@delete');

            Route::get('{id}/get_html_block', 'OrganizationsController@get_html_block');

            Route::get('/organizations/{id}/tariff/{product_id}', 'OrganizationsController@getViewTariff');
            Route::post('/organizations/{id}/tariff/{product_id}', 'OrganizationsController@saveTariff');

            Route::get('organizations/get_table', 'OrganizationsController@get_table');
            Route::resource('organizations', 'OrganizationsController');

            Route::resource('org_bank_account', 'OrgBankAccountController');

            Route::resource('organizations/{org_id}/scans', 'ScansController');

            Route::post('organizations/{org_id}/get_users_table', 'OrganizationsController@get_users_table');

            Route::delete('organizations/{org_id}/delete_scans/{file_id}', 'ScansController@deleteScans');

            Route::delete('delete_scans/{file_id}', 'ScansController@deleteScans');

        });

        Route::resource('products', 'ProductsController');

        Route::group(['namespace' => 'ProductsPrograms', 'prefix' => 'products/{id}/edit/programs/{program}'], function () {

            Route::get('/', 'ProductsProgramsController@index');

            Route::post('/', 'ProductsProgramsController@save');

            Route::delete('/', 'ProductsProgramsController@destroy');

        });

        Route::group(['namespace' => 'Products', 'prefix' => 'products/{product_id}/edit/info/'], function () {

            Route::get('/', 'ProductsInfoController@index');

            Route::get('/{type}/{id}/edit', 'ProductsInfoController@edit');
            Route::post('/{type}/{id}/edit', 'ProductsInfoController@save');

            Route::post('/sort', 'ProductsInfoController@sort');

            Route::delete('/{type}/delete/{id}', 'ProductsInfoController@destroy');

        });

        Route::group(['namespace' => 'Products', 'prefix' => 'products/{product_id}/edit/special-settings/'], function () {

            Route::get('/', 'ProductsSpecialSettingsController@index');
            Route::post('/', 'ProductsSpecialSettingsController@save');

            Route::post('/save_files', 'ProductsSpecialSettingsController@save_files');
            Route::delete('/delete-file/{files_name}', 'ProductsSpecialSettingsController@delete_file');

            Route::post('/sort', 'ProductsSpecialSettingsController@risks_sort');

            Route::get('/official_discount/{discount_id}/', 'ProductsSpecialSettingsController@official_discount_edit');
            Route::post('/official_discount/{discount_id}/', 'ProductsSpecialSettingsController@official_discount_save');
            Route::delete('/official_discount/{discount_id}/', 'ProductsSpecialSettingsController@official_discount_destroy');

            Route::get('/{risks_id}/risks', 'ProductsSpecialSettingsController@risks_edit');
            Route::post('/{risks_id}/risks', 'ProductsSpecialSettingsController@risks_save');
            Route::delete('/{risks_id}/risks', 'ProductsSpecialSettingsController@risks_destroy');

            Route::get('/assistance_info/{assistance_id}/edit', 'ProductsSpecialSettingsController@assistance_edit');
            Route::post('/assistance_info/{assistance_id}/edit', 'ProductsSpecialSettingsController@assistance_save');
            Route::delete('/assistance_info/{assistance_id}/edit', 'ProductsSpecialSettingsController@assistance_destroy');

            Route::get('/{risks_id}/table-tariff', 'ProductsSpecialSettingsController@tableTariffEdit');
            Route::post('/{risks_id}/table-tariff', 'ProductsSpecialSettingsController@tableTariffSave');




            Route::group(['namespace' => 'Program\Mortgage', 'prefix' => '/mortgage'], function () {

                Route::get('/get-form-html', 'ProgramMortgageController@getFormHtml');


                Route::get('/baserate-life/{baserate_id}', 'ProgramMortgageController@getBaserateLife');
                Route::post('/baserate-life/{baserate_id}', 'ProgramMortgageController@saveBaserateLife');
                Route::delete('/baserate-life/{baserate_id}', 'ProgramMortgageController@deleteBaserateLife');

                Route::get('/baserate-property/{baserate_id}', 'ProgramMortgageController@getBaserateProperty');
                Route::post('/baserate-property/{baserate_id}', 'ProgramMortgageController@saveBaserateProperty');
                Route::delete('/baserate-property/{baserate_id}', 'ProgramMortgageController@deleteBaserateProperty');

                Route::get('/baserate-title/{baserate_id}', 'ProgramMortgageController@getBaserateTitle');
                Route::post('/baserate-title/{baserate_id}', 'ProgramMortgageController@saveBaserateTitle');
                Route::delete('/baserate-title/{baserate_id}', 'ProgramMortgageController@deleteBaserateTitle');


            });

            Route::group(['namespace' => 'Program', 'prefix' => '/program/{program_id}'], function () {

                Route::group(['namespace' => 'Kasko', 'prefix' => '/kasko'], function () {

                    Route::get('/', 'ProgramKaskoController@index');
                    Route::get('/get-form-html', 'ProgramKaskoController@getFormHtml');

                    Route::get('/auto/mark', 'ProgramKaskoController@getAutoMark');
                    Route::get('/auto/models', 'ProgramKaskoController@getAutoModels');

                    Route::get('/auto/def-baserate', 'ProgramKaskoController@saveDefBaserate');

                    Route::get('/auto/baserate', 'ProgramKaskoController@getBaserate');
                    Route::post('/auto/baserate/save', 'ProgramKaskoController@saveBaserate');

                    Route::get('/auto/equipment/{equipment_id}', 'ProgramKaskoController@getEquipment');
                    Route::post('/auto/equipment/{equipment_id}', 'ProgramKaskoController@saveEquipment');
                    Route::delete('/auto/equipment/{equipment_id}', 'ProgramKaskoController@deleteEquipment');

                    Route::get('/auto/services/{service_id}', 'ProgramKaskoController@getService');
                    Route::post('/auto/services/{service_id}', 'ProgramKaskoController@saveService');
                    Route::delete('/auto/services/{service_id}', 'ProgramKaskoController@deleteService');

                    Route::get('/auto/product/{k_product_id}', 'ProgramKaskoController@getKProduct');
                    Route::post('/auto/product/{k_product_id}', 'ProgramKaskoController@saveKProduct');
                    Route::delete('/auto/product/{k_product_id}', 'ProgramKaskoController@deleteKProduct');

                    Route::post('/default', 'ProgramKaskoController@saveDefault');
                    Route::post('/documents', 'ProgramKaskoController@saveDocuments');

                    Route::get('/coefficients/{category}', 'ProgramKaskoController@getCoefficientsList');
                    Route::get('/coefficients/{category}/{coefficient_id}', 'ProgramKaskoController@getCoefficient');
                    Route::get('/coefficients/{category}/{coefficient_id}/element', 'ProgramKaskoController@getCoefficientElement');
                    Route::post('/coefficients/{category}/{coefficient_id}', 'ProgramKaskoController@saveCoefficient');
                    Route::delete('/coefficients/{category}/{coefficient_id}', 'ProgramKaskoController@deleteCoefficient');

                    Route::get('/dopwhere/{category}', 'ProgramKaskoController@getDopwhereList');
                    Route::get('/dopwhere/{category}/{dopwhere_id}', 'ProgramKaskoController@getDopwhere');
                    Route::get('/dopwhere/{category}/{dopwhere_id}/element', 'ProgramKaskoController@getDopwhereElement');
                    Route::post('/dopwhere/{category}/{dopwhere_id}', 'ProgramKaskoController@saveDopwhere');
                    Route::delete('/dopwhere/{category}/{dopwhere_id}', 'ProgramKaskoController@deleteDopwhere');



                });

                Route::group(['namespace' => 'Arbitration', 'prefix' => '/arbitration'], function () {

                    Route::get('/', 'ProgramArbitrationController@index');

                    Route::get('/get-form-html', 'ProgramArbitrationController@getFormHtml');

                    Route::post('/default', 'ProgramArbitrationController@saveDefault');

                    Route::post('/documents', 'ProgramArbitrationController@saveDocuments');

                    Route::post('/save_files', 'ProgramArbitrationController@save_files');

                    Route::delete('/delete-file/{files_name}', 'ProgramArbitrationController@delete_file');

                    Route::post('/tariff', 'ProgramArbitrationController@saveTariff');

                    Route::get('/coefficients/{category}', 'ProgramArbitrationController@getCoefficientsList');
                    Route::get('/coefficients/{category}/{coefficient_id}', 'ProgramArbitrationController@getCoefficient');
                    Route::get('/coefficients/{category}/{coefficient_id}/element', 'ProgramArbitrationController@getCoefficientElement');
                    Route::post('/coefficients/{category}/{coefficient_id}', 'ProgramArbitrationController@saveCoefficient');
                    Route::delete('/coefficients/{category}/{coefficient_id}', 'ProgramArbitrationController@deleteCoefficient');

                });

            });

        });

        Route::group(['namespace' => 'InsuranceCompanies', 'prefix' => 'insurance_companies'], function () {

            Route::get('/', 'InsuranceCompaniesController@index');
            Route::get('/{id}', 'InsuranceCompaniesController@edit');
            Route::post('/{id}', 'InsuranceCompaniesController@save');

            Route::group(['namespace' => 'InstallmentAlgorithms', 'prefix' => '/{id}/installment_algorithms'], function () {

                Route::get('/{algorithm_id}', 'InstallmentAlgorithmsController@edit');
                Route::post('/{algorithm_id}', 'InstallmentAlgorithmsController@save');

            });

            Route::group(['namespace' => 'TypeBso', 'prefix' => '/{id}/type_bso'], function () {

                Route::get('/{type_bso_id}', 'TypeBsoController@edit');
                Route::post('/{type_bso_id}', 'TypeBsoController@save');

                Route::group(['prefix' => '/{type_bso_id}/bso_serie'], function () {

                    Route::get('/{bso_serie_id}', 'BsoSerieController@edit');
                    Route::post('/{bso_serie_id}', 'BsoSerieController@save');

                });

                Route::group(['prefix' => '/{type_bso_id}/bso_serie/{bso_serie_id}/bso_dop_serie'], function () {

                    Route::get('/{bso_dop_serie_id}', 'BsoSerieController@dop_edit');
                    Route::post('/{bso_dop_serie_id}', 'BsoSerieController@dop_save');

                });

            });

            Route::group(['namespace' => 'BsoSuppliers', 'prefix' => '/{id}/bso_suppliers'], function () {

                Route::get('/{bso_supplier_id}', 'BsoSuppliersController@edit');
                Route::post('/{bso_supplier_id}', 'BsoSuppliersController@save');

                Route::resource('/{bso_supplier_id}/hold_kv', 'HoldKvController');

                Route::get('/{bso_supplier_id}/hold_kv/{hold_kv_id}/group_info', 'HoldKvController@get_group_info');
                Route::post('/{bso_supplier_id}/hold_kv/{hold_kv_id}/group_save', 'HoldKvController@group_save');
                Route::post('/{bso_supplier_id}/hold_kv/{hold_kv_id}/group_delete', 'HoldKvController@group_delete');

                Route::get('/{bso_supplier_id}/hold_kv/{hold_kv_id}/installment_algorithms_payment/{group_id}/{algorithm_id}', 'AlgorithmsPaymentController@edit');

                Route::post('/{bso_supplier_id}/hold_kv/{hold_kv_id}/installment_algorithms_payment/{group_id}/{algorithm_id}', 'AlgorithmsPaymentController@save');

                Route::delete('/{bso_supplier_id}/hold_kv/{hold_kv_id}/installment_algorithms_delete/{algorithm_id}', 'AlgorithmsPaymentController@delete');

                Route::get('/{bso_supplier_id}/hold_kv/{hold_kv_id}/installment_algorithms_info', 'AlgorithmsPaymentController@info');

                Route::get('/{bso_supplier_id}/hold_kv/{hold_kv_id}/matching-terms', 'MatchingTermsController@getMatchingTerms');
                Route::get('/{bso_supplier_id}/hold_kv/{hold_kv_id}/matching-terms/{group_id}/{type}/{matching_id}', 'MatchingTermsController@edit');
                Route::post('/{bso_supplier_id}/hold_kv/{hold_kv_id}/matching-terms/{group_id}/{type}/{matching_id}', 'MatchingTermsController@save');
                Route::delete('/{bso_supplier_id}/hold_kv/{hold_kv_id}/matching-terms/{group_id}/{type}/{matching_id}', 'MatchingTermsController@delete');

                Route::get('/{bso_supplier_id}/hold_kv/{hold_kv_id}/supplier_form', 'HoldKvController@supplier_form');
                Route::post('/{bso_supplier_id}/hold_kv/{hold_kv_id}/supplier_form', 'HoldKvController@supplier_form');

                Route::get('/{bso_supplier_id}/hold_kv/{hold_kv_id}/supplier_form/{version_id}', 'HoldKvController@supplier_form_edit');
                Route::post('/{bso_supplier_id}/hold_kv/{hold_kv_id}/supplier_form/{version_id}', 'HoldKvController@supplier_form_edit');

                Route::post('/{bso_supplier_id}/hold_kv/{hold_kv_id}/supplier_select_form', 'HoldKvController@supplier_select_form');

                Route::group(['namespace' => 'FinancialPolicy', 'prefix' => '/{bso_supplier_id}/financial_policy'], function () {

                    Route::get('/{financial_policy_id}', 'FinancialPolicyController@edit');
                    Route::post('/{financial_policy_id}', 'FinancialPolicyController@save');

                    Route::group(['prefix' => '/{financial_policy_id}/segments'], function () {

                        Route::get('/{segment_id}', 'SegmentsController@edit');
                        Route::post('/{segment_id}', 'SegmentsController@save');

                    });

                });

            });

        });

        Route::group(['prefix' => '/auto', 'namespace' => 'Auto'], function () {

            Route::get('/', 'AutoController@index');

            Route::group(['prefix' => '/categories'], function () {

                Route::get('/', 'CategoriesController@CategoriesPage');

                Route::get('/edit/{categoryId?}', 'CategoriesController@CategoryEditPage')->name('category-edit-page');

                Route::get('/create', 'CategoriesController@CategoryCreatePage')->name('category-create-page');

                Route::post('/save', 'CategoriesController@CategorySave')->name('category-save');

                Route::post('/delete', 'CategoriesController@CategoryDelete')->name('category-delete');

            });

            Route::group(['prefix' => '/marks'], function () {

                Route::get('/', 'MarksController@MarksPage');

                Route::get('/category-marks', 'MarksController@GetCategoryMarks')->name('category-marks');

                Route::get('/edit/{markId?}', 'MarksController@MarkEditPage')->name('mark-edit-page');

                Route::get('/create', 'MarksController@MarkCreatePage')->name('mark-create-page');

                Route::post('/save', 'MarksController@MarkSave')->name('mark-save');

                Route::post('/delete', 'MarksController@MarkDelete')->name('mark-delete');

            });

            Route::group(['prefix' => '/models'], function () {

                Route::get('/', 'ModelsController@ModelsPage');

                Route::get('/mark-marks', 'ModelsController@GetMarkModels')->name('mark-models');

                Route::get('/edit/{modelId?}', 'ModelsController@ModelEditPage')->name('model-edit-page');

                Route::get('/create', 'ModelsController@ModelCreatePage')->name('model-create-page');

                Route::post('/save', 'ModelsController@ModelSave')->name('model-save');

                Route::post('/delete', 'ModelsController@ModelDelete')->name('model-delete');

            });

            Route::group(['prefix' => '/colors'], function () {

                Route::get('/', 'ColorsController@ColorsPage');

                Route::get('/edit/{colorId?}', 'ColorsController@ColorEditPage')->name('color-edit-page');

                Route::get('/create', 'ColorsController@ColorCreatePage')->name('color-create-page');

                Route::post('/save', 'ColorsController@ColorSave')->name('color-save');

                Route::post('/delete', 'ColorsController@ColorDelete')->name('color-delete');

            });

            Route::group(['prefix' => '/anti-theft-system'], function () {

                Route::get('/', 'AntiTheftSystemsController@AntiTheftSystemsPage');

                Route::get('/edit/{antiTheftSystemId?}', 'AntiTheftSystemsController@AntiTheftSystemEditPage')->name('anti-theft-system-edit-page');

                Route::get('/create', 'AntiTheftSystemsController@AntiTheftSystemCreatePage')->name('anti-theft-system-create-page');

                Route::post('/save', 'AntiTheftSystemsController@AntiTheftSystemSave')->name('anti-theft-system-save');

                Route::post('/delete', 'AntiTheftSystemsController@AntiTheftSystemDelete')->name('anti-theft-system-delete');

            });

        });

    });

    Route::group(['prefix' => 'users', 'namespace' => 'Users'], function () {

        Route::get('frame', 'UsersFrameController@frame');
        Route::post('frame', 'UsersFrameController@save');

        Route::get('limit', 'UsersFrameController@limit');
        Route::post('limit', 'UsersFrameController@save_limit');

        Route::get('users/get_table', 'UsersController@get_table');

        Route::get('/users/{id}/tariff/{product_id}', 'UsersController@getViewTariff');
        Route::post('/users/{id}/tariff/{product_id}', 'UsersController@saveTariff');

        Route::resource('users', 'UsersController', ['except' => 'destroy']);

        Route::resource('roles', 'Roles\RolesController');

        Route::group(['prefix' => 'roles', 'namespace' => 'Roles'], function () {

            Route::get('{role_id}/permission/{permission_id}/subpermissions', 'SubpermissionController@index');
            Route::post('{role_id}/permission/{permission_id}/subpermissions', 'SubpermissionController@save');

        });

        Route::get('/{user_id}/generate_contract', 'UsersController@generate_contract');

        Route::post('actions/search_front_user/suggest/party', 'ActionsController@search_fron_users');

        Route::group(['namespace' => 'Users', 'prefix' => 'users/{user_id}'], function () {

            Route::resource('scans', 'ScansController');
            Route::delete('delete_scans/{file_id}', 'ScansFileController@deleteScans');

        });

        Route::group(['prefix' => 'notification', 'namespace' => 'Notification'], function () {

            Route::get('/', 'NotificationController@index');
            Route::get('get_table', 'NotificationController@get_table');

            Route::post('{id}/read', 'NotificationController@read');

        });

        Route::group(['prefix' => 'promocode', 'namespace' => 'PromoCode'], function () {

            Route::get('/', 'PromoCodeController@index');

            Route::get('/create', 'PromoCodeController@create');

            Route::post('/create', 'PromoCodeController@save');

            Route::get('/{user_id}', 'PromoCodeController@view');

        });

    });

    Route::group(['prefix' => 'account'], function () {

        Route::post('photo', 'Account\PhotoController@store');

        Route::post('size', 'Account\UserInfoController@setTextSize');

        Route::get('password', 'Account\UserInfoController@getPassword');
        Route::post('password', 'Account\UserInfoController@setPassword');

        Route::group(['prefix' => 'table_setting'], function () {

            Route::get('/{table_key}/edit/', 'Account\TableSettingController@edit');
            Route::post('/{table_key}/save/', 'Account\TableSettingController@save');

        });

        Route::get('notification/clear-all', 'Account\UserInfoController@clearNotification');
        Route::get('notification/{id}', 'Account\UserInfoController@getNotification');

    });

    Route::get('cron/planning', 'Cron\CronController@planning');

    Route::group(['prefix' => 'exports', 'namespace' => 'Exports'], function () {
        Route::get('table2excel', 'ExportsController@table2excel');
    });

    Route::group(['prefix' => 'subject', 'namespace' => 'General\Subjects'], function () {

        Route::get('/fl', 'GeneralSubjectsListController@list_fl');

        Route::get('/ul', 'GeneralSubjectsListController@list_ul');

        Route::post('/get_table/{type}', 'GeneralSubjectsListController@get_table');

    });

    Route::group(['prefix' => 'general', 'namespace' => 'General'], function () {

        Route::group(['prefix' => 'subjects', 'namespace' => 'Subjects'], function () {

            Route::get('/create', 'GeneralSubjectsController@search');

            Route::post('/find', 'GeneralSubjectsController@searchFind');

            Route::post('/create', 'GeneralSubjectsController@create');

            Route::group(['prefix' => '/frame/{id}'], function () {

                Route::get('/', 'GeneralSubjectsController@frame');
                Route::post('/', 'GeneralSubjectsController@frameSaveData');

            });

            Route::group(['prefix' => '/edit/{id}'], function () {

                Route::get('/', 'GeneralSubjectsController@edit');
                Route::post('/', 'GeneralSubjectsController@saveData');

                Route::get('/get_html_block', 'GeneralSubjectsController@get_html_block');

                Route::post('/podft', 'GeneralSubjectsController@savePodft');
                Route::post('/podft-check', 'GeneralSubjectsController@checkPodft');
                Route::post('/update-info-podft', 'GeneralSubjectsController@updateInfoPodft');

                Route::post('/special', 'GeneralSubjectsController@saveSpecial');

                Route::get('/document/{doc_id}', 'GeneralSubjectsController@getDocument');
                Route::post('/document/{doc_id}', 'GeneralSubjectsController@saveDocument');
                Route::delete('/document/{doc_id}', 'GeneralSubjectsController@deleteDocument');

                Route::group(['prefix' => '/action'], function () {

                    Route::get('/interactions-connections/{ic_id}', 'ActionGeneralSubjectsController@setInteractionsConnections');
                    Route::post('/interactions-connections/{ic_id}', 'ActionGeneralSubjectsController@saveInteractionsConnections');

                    Route::get('/founders/{founder_id}', 'ActionGeneralSubjectsController@setFounders');
                    Route::post('/founders/{founder_id}', 'ActionGeneralSubjectsController@saveFounders');
                    Route::delete('/founders/{founder_id}', 'ActionGeneralSubjectsController@deleteFounders');

                });

            });

        });

    });

    Route::group(['prefix' => 'integration', 'namespace' => 'Integration'], function () {

        Route::group(['prefix' => 'vtigercrm', 'namespace' => 'VtigerCRM'], function () {

            Route::get('/', 'VtigerCRMController@index');

            Route::post('/start-info', 'VtigerCRMController@startInfo');

            Route::post('/updata-info', 'VtigerCRMController@updataInfo');

            Route::post('/connection', 'VtigerCRMController@connectionInfo');

            Route::post('/clear-system', 'VtigerCRMController@clearSystem');

        });

        Route::group(['prefix' => 'tit', 'namespace' => 'TitCRM'], function () {

            Route::get('/', 'TitCRMController@index');

            Route::post('/start-info', 'TitCRMController@startInfo');

            Route::post('/updata-info', 'TitCRMController@updataInfo');

            Route::post('/clear-system', 'TitCRMController@clearSystem');


            Route::post('/update-mark-model', 'TitCRMController@updateMarkModel');

        });

        Route::group(['prefix' => 'verna', 'namespace' => 'Verna'], function () {

            Route::get('/', 'VernaController@index');

            Route::get('/updata', 'VernaController@updata');

        });

    });

    Route::get('cron/test', 'Cron\CronController@test');
    Route::get('cron/users/update-pass', 'Cron\CronController@updateAllUserPass');


    Route::group(['prefix' => 'suggestions/dadata', 'namespace' => 'DaData'], function () {

        Route::get('detectAddressByIp', 'DaDataController@detectAddressByIp');

        Route::get('status/address', 'DaDataController@status_address');
        Route::get('status/fio', 'DaDataController@status_fio');
        Route::get('status/party', 'DaDataController@status_party');

        Route::post('suggest/address', 'DaDataController@address');
        Route::post('suggest/fio', 'DaDataController@fio');
        Route::post('suggest/party', 'DaDataController@party');

        Route::group(['prefix' => '/general'], function () {

            Route::get('detectAddressByIp', 'DaDataController@detectAddressByIp');

            Route::get('status/party', 'DaDataController@status_party');

            Route::get('status/fio', 'DaDataController@status_fio');

            Route::post('suggest/party', 'DaDataController@generalUL');

            Route::post('suggest/fio', 'DaDataController@generalFL');

        });

        Route::group(['prefix' => '/prolongation'], function () {

            Route::get('detectAddressByIp', 'DaDataController@detectAddressByIp');

            Route::get('status/party', 'DaDataController@status_party');

            Route::post('suggest/party', 'DaDataController@prolongation');

        });

        Route::group(['prefix' => '/organization'], function () {

            Route::get('detectAddressByIp', 'DaDataController@detectAddressByIp');

            Route::get('status/party', 'DaDataController@status_party');

            Route::post('suggest/party', 'DaDataController@organization');

        });

    });

});

Route::get('cron/actuals/currency', 'Cron\CronController@actualsCurrency');

Route::get('cron/send/notifications', 'Cron\CronController@sendNotifications');



Route::resource('files', 'FilesController', ['only' => ['show', 'destroy']]);

Route::group(['prefix' => 'thumbs'], function () {

    Route::get('/{filename}', 'FilesController@thumb');

});

Route::post('/suggest/{type}', 'SuggestsController@suggest');

Route::group(['prefix' => '/custom_suggestions/{name}'], function () {

    Route::post('/suggest/{type}', 'CustomsuggestsController@suggest');

});

Route::group(['prefix' => '/clients/check', 'namespace' => 'Client\Check'], function () {

    Route::get('/payment/{contract_id}/{payment_id}', 'ClientsCheckController@payment');

    Route::get('/payment/', 'ClientsCheckController@clientPayment');

});

Route::post('/menu-settings', 'MenuSettingsController@update');

Auth::routes();

Route::get('/logout', 'Auth\LoginController@logout');

//Route::get('gpx/{order_id}', 'Orders\FactRoadsController@gpx');

Route::get('/', 'HomeController@index');

Route::post('hooks', 'HooksController@store');