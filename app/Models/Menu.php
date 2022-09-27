<?php

namespace App\Models;

class Menu {

    public static function all() {
        $menuItems = [
            'bso' => [
                'ico' => 'ico-costs',
                'form_title' => 'bso',
                'form_button_name' => '',
                'form_button_class' => '',
                'form_button_link' => '',
                'links' => [
                    ['link' => 'add_bso_warehouse', 'ico' => 'ico-etc-parts', 'name' => 'add_bso_warehouse'],
                    ['link' => 'transfer', 'ico' => 'clients_dark', 'name' => 'bso_receive_transmit'],
                    ['link' => 'inventory_bso', 'ico' => 'ico-calendar', 'name' => 'inventory_bso'],
                    ['link' => 'inventory_agents', 'ico' => 'ico-calendar', 'name' => 'inventory_agents'],
                ],
            ],
            'bso_acts' => [
                'ico' => 'ico-orders',
                'form_title' => 'bso_acts',
                'form_button_name' => '',
                'form_button_class' => '',
                'form_button_link' => '',
                'links' => [
                    ['link' => 'acts_reserve', 'ico' => 'ico-orders', 'name' => 'acts_reserve'],
                    ['link' => 'acts_transfer', 'ico' => 'clients_dark', 'name' => 'acts_transfer'],
                    ['link' => 'acts_implemented', 'ico' => 'ico-calendar', 'name' => 'acts_implemented'],
                    ['link' => 'acts_transfer_tp', 'ico' => 'organisations_dark', 'name' => 'acts_transfer_tp'],
                ],
            ],

            'subject' => [
                'ico' => 'ico-users',
                'form_title' => 'subjects',
                'form_button_name' => '',
                'form_button_class' => '',
                'form_button_link' => '',
                'links' => [
                    ['link' => 'fl', 'ico' => 'ico-users', 'name' => 'fl'],
                    ['link' => 'ul', 'ico' => 'organisations_dark', 'name' => 'ul'],
                ],
            ],

            'contracts' => [
                'ico' => 'ico-orders',
                'form_title' => 'contracts',
                'form_button_name' => '',
                'form_button_class' => '',
                'form_button_link' => '',
                'links' => [

                    ['link' => 'online', 'ico' => 'ico-etc-parts', 'name' => 'online'],
                    ['link' => 'prolongation', 'ico' => 'ico-calendar', 'name' => 'prolongation'],
                    ['link' => 'search', 'ico' => 'ico-fin-politics', 'name' => 'search'],
                ],
            ],

            'matching' => [
                'ico' => 'ico-notice',
                'form_title' => 'matching',
                'form_button_name' => '',
                'form_button_class' => '',
                'form_button_link' => '',
                'links' => [

                    ['link' => 'underwriting', 'ico' => 'ico-orders', 'name' => 'underwriting'],
                    ['link' => 'security-service', 'ico' => 'clients_dark', 'name' => 'security_service'],
                    ['link' => 'archive', 'ico' => 'ico-calendar', 'name' => 'archive'],
                ],
            ],

            'orders' => [
                'ico' => 'ico-orders',
                'form_title' => 'orders',
                'form_button_name' => '',
                'form_button_class' => '',
                'form_button_link' => '',
                'links' => [
                    ['link' => 'pso', 'ico' => 'clients_dark', 'name' => 'pso'],
                    ['link' => 'damages', 'ico' => 'ico-fin-politics', 'name' => 'damages'],
                ],
            ],

            'reports' => [
                'ico' => 'ico-orders',
                'form_title' => 'reports',
                'form_button_name' => '',
                'form_button_class' => '',
                'form_button_link' => '',
                'links' => [
                    ['link' => 'reports_sk', 'ico' => 'ico-credit-cards', 'name' => 'reports_sk'],
                ],
            ],


            'cashbox' => [
                'ico' => 'ico-cashbox',
                'form_title' => 'cashbox',
                'form_button_name' => '',
                'form_button_class' => '',
                'form_button_link' => '',
                'links' => [
                    ['link' => 'invoice', 'ico' => 'ico-credit-cards', 'name' => 'invoice'],
                    ['link' => 'payment_reports', 'ico' => 'ico-fin-politics', 'name' => 'payment_reports'],
                ],
            ],

            'analitics' => [
                'ico' => 'ico-analitics',
                'form_title' => 'analitics',
                'form_button_name' => '',
                'form_button_class' => '',
                'form_button_link' => '',
                'links' => [
                    ['link' => 'sales', 'ico' => 'ico-fin-politics', 'name' => 'sales'],
                    ['link' => 'total', 'ico' => 'ico-fin-politics', 'name' => 'total'],
                ],
            ],

            'directories' => [
                'ico' => 'ico-costs',
                'form_title' => 'directories',
                'form_button_name' => '',
                'form_button_class' => '',
                'form_button_link' => '',
                'links' => [
                    ['link' => 'insurance_companies', 'ico' => 'ico-insurance', 'name' => 'insurance_companies'],
                    ['link' => 'organizations/organizations', 'ico' => 'organisations_dark', 'name' => 'organizations'],
                    ['link' => 'products', 'ico' => 'ico-etc-parts', 'name' => 'products'],
                    ['link' => 'auto', 'ico' => 'ico-insurance', 'name' => 'auto'],
                ],
            ],
            'users' => [
                'ico' => 'ico-users',
                'form_title' => 'users',
                'form_button_name' => 'form.buttons.create',
                'form_button_class' => '',
                'form_button_link' => '/users/users/create',
                'links' => [
                    ['link' => 'users', 'ico' => 'ico-employers', 'name' => 'users'],
                    ['link' => 'roles', 'ico' => 'ico-user-roles', 'name' => 'roles'],
                    ['link' => 'promocode', 'ico' => 'ico-etc-parts', 'name' => 'promocode'],
                ],
            ],

            'integration' => [
                'ico' => 'ico-settings',
                'form_title' => 'integration',
                'form_button_name' => '',
                'form_button_class' => '',
                'form_button_link' => '',
                'links' => [
                    ['link' => 'tit', 'ico' => 'ico-employers', 'name' => 'tit_old'],
                    /*
                     ['link' => 'vtigercrm', 'ico' => 'ico-employers', 'name' => 'vtigercrm'],
                    ['link' => 'verna', 'ico' => 'ico-etc-parts', 'name' => 'verna'],
                     */
                ],
            ],


            'settings' => [
                'ico' => 'ico-settings',
                'form_title' => 'settings',
                'form_button_name' => '',
                'form_button_class' => '',
                'form_button_link' => '',
                'links' => [

                    ['link' => 'country', 'ico' => 'ico-orders-map', 'name' => 'country'],
                    ['link' => 'citys', 'ico' => 'organisations_dark', 'name' => 'citys'],
                    ['link' => 'currency', 'ico' => 'ico-salary', 'name' => 'currency'],
                    ['link' => 'points_sale', 'ico' => 'ico-orders-map', 'name' => 'points_sale'],
                    ['link' => 'departments', 'ico' => 'ico-units', 'name' => 'departments'],
                    ['link' => 'financial_policy', 'ico' => 'ico-fin-politics', 'name' => 'financial_policy'],
                    ['link' => 'payment_methods', 'ico' => 'ico-credit-cards', 'name' => 'payment_methods'],
                    ['link' => 'installment_algorithms_payment', 'ico' => 'ico-banks', 'name' => 'installment_algorithms_payment'],
                    ['link' => 'banks', 'ico' => 'ico-banks', 'name' => 'banks'],
                    ['link' => 'type_org', 'ico' => 'ico-etc-parts', 'name' => 'type_org'],
                    ['link' => 'templates', 'ico' => 'ico-etc-parts', 'name' => 'templates'],
                    ['link' => 'system', 'ico' => 'ico-etc-parts', 'name' => 'system'],
                    ['link' => 'user_balance', 'ico' => 'ico-credit-cards', 'name' => 'user_balance'],
                    ['link' => 'incomes_expenses_categories', 'ico' => 'ico-salary', 'name' => 'incomes_expenses_categories'],
                ],
            ],
        ];

        return collect($menuItems);
    }

}
