<?php

namespace App\Http\Controllers;

class HooksController extends Controller {

	public function store() {

		$folder = "/srv/www/energogarant";

		echo shell_exec("cd $folder && git fetch");
		echo shell_exec("cd $folder && git merge");
		echo shell_exec("cd $folder && php composer.phar install");
		echo shell_exec("cd $folder && php artisan migrate");
		echo shell_exec("cd $folder && php artisan db:seed");
		echo shell_exec("cd $folder && php artisan config:cache");

	}

}