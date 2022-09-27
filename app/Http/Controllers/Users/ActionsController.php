<?php

namespace App\Http\Controllers\Users;

use App\Classes\Export\ExportManager;
use App\Helpers\Visible;
use App\Classes\Export\Replacers\WordReplacer;
use App\Http\Controllers\Controller;
use App\Models\Characters\Agent;
use App\Models\Log\LogEvents;
use App\Models\Organizations\Organization;
use App\Models\Settings\ExportItem;
use App\Models\Settings\Template;
use App\Models\Settings\TemplateCategory;
use App\Models\Subject\Juridical;
use App\Models\Subject\Physical;
use App\Models\Subject\Type as SubjectType;
use App\Models\User;
use App\Models\Users\Role;
use App\Models\Users\SalaryType;
use App\Repositories\FilesRepository;
use App\Services\Front\IntegrationFront;
use Illuminate\Http\Request;
use Validator;
use Mail;
use App\Mail\UserAdd;

class ActionsController extends Controller {


    public function __construct() {

    }

    public function search_fron_users(Request $request)
    {

        $json = \GuzzleHttp\json_decode($request->getContent());
        $count = $json->count;
        $like_query = $json->query;

        $front = new IntegrationFront();

        $res = [];
        $res["suggestions"] = $front->search_users($like_query);

        return response()->json($res);

    }


}
