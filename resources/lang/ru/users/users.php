<?php

return [
    'index' => [
        'name'         => 'ФИО',
        'email'        => 'Email',
        'role'         => 'Роль',
        'department'   => 'Подразделение',
        'subject_type' => 'Тип субъекта',
    ],
    'edit'  => [
        'name'                   => 'ФИО',
        'role'                   => 'Роль',
        'timezone'               => 'Часовой пояс',
        'email'                  => 'Email/Логин',
        'password'               => 'Пароль',
        'type'                   => 'Тип',
        'subject_type'           => 'Тип субъекта',
        'physical'               => 'Физическое лицо',
        'juridical'              => 'Юридическое лицо',
        'first_name'             => 'Имя',
        'second_name'            => 'Фамилия',
        'middle_name'            => 'Отчество',
        'passport_series'        => 'Серия паспорта',
        'passport_number'        => 'Номер паспорта',
        'driver_license_series'  => 'Серия водительского удостоверения',
        'driver_license_number'  => 'Номер водительского удостоверения',
        'driver_experience_year' => 'Год начала стажа',
        'mobile_phone'           => 'Мобильный телефон',
        'work_phone'             => 'Рабочий телефон',
        'ogrn'                   => 'ОГРН',
        'inn'                    => 'ИНН',
        'created_at'             => 'Дата создания',
        'bik'                    => 'БИК',
        'bank'                   => 'Банк',
        'rs'                     => 'Расчётный счёт',
        'scans'                  => 'Сканы',
        'title'                  => 'Название',
        'is_driver'              => 'Является водителем',
        'organization'           => 'Организация',
        'department'             => 'Подразделение',
        'image'                  => 'Фотография',
        'salary'                 => 'Зарплата',
        'salary_fix'             => 'Зарплата фиксированная',
        'salary_percentage'      => 'Процент от заказа',
        'salary_type'            => 'Тип',
        'сurator'                => 'Куратор',
        'fin_group'              => 'Финансовая группа',
        'fin_group_default'      => 'По умолчанию',


        'is_parent' => 'Руководитель',

        'salary_types'       => [
            'official'   => 'Официальная',
            'unofficial' => 'Неофициальная',
        ],
        'financial_policies' => [
            'fixed'      => [
                'title'        => 'Финполитики базовые',
                'title_column' => 'Название',
                'begin_date'   => 'Дата начала',
                'actual'       => 'Актуальность',
                'amount'       => 'Сумма',
                'payment_type' => 'Тип оплаты',
                'period_type'  => 'Период',
                'state'        => 'Статус',
                'created'      => 'Дата создания',
                'department'   => 'Подразделение',
            ],
            'percentage' => [
                'title'        => 'Финполитики процентные',
                'title_column' => 'Название',
                'begin_date'   => 'Дата начала',
                'actual'       => 'Актуальность',
                'percent'      => 'Процент',
            ],
            'based'      => 'Базовые',
        ],
    ],
];