<?php

namespace App\Integrations\Test\v1;

/**
 * На этот класс нужно ссылаться из бд
 * /settings/system/integration/1/edit/13
 */
class Api {

    private $version;
    private $product;
    private $credentials;

    public function setProduct($product) {
        $this->product = $product;
    }

    public function setCredentials($credentials) {
        $this->credentials = $credentials;
    }

    public function handle($method) {
        return new \stdClass();
    }

    public function getMainForm() {
        return [
            ['name' => 'url', 'label' => 'Url front', 'type' => 'text', 'requred' => true, 'placeholder' => 'http://web-api.com:9999', 'description' => 'Тестовое описание'],
            ['name' => 'token', 'label' => 'token', 'type' => 'text', 'requred' => true, 'placeholder' => 'erjgkerj34jgj45kgfr', 'description' => 'Тестовое описание 2'],
        ];
    }

    public function getSupplierForm() {
        return [
            ['name' => 'user', 'label' => 'Логин', 'type' => 'text', 'requred' => true, 'placeholder' => 'user', 'description' => 'Логин от лк'],
            ['name' => 'password', 'label' => 'Пароль', 'type' => 'text', 'requred' => true, 'placeholder' => '', 'description' => 'Пароль от лк'],
        ];
    }

}
