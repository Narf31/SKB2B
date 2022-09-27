<?php

namespace App\Providers;

use App\Vendor\Collective\Html\NewFormBuilder;
use Collective\Html\HtmlServiceProvider as BaseServiceProvider;

class NewHtmlServiceProvider extends BaseServiceProvider
{


    protected function registerFormBuilder()
    {
        $this->app->singleton('form', function ($app) {

            $form = new NewFormBuilder($app['html'], $app['url'], $app['view'], $app['session.store']->token(), $app['request']);

            return $form->setSessionStore($app['session.store']);
        });
    }
}

