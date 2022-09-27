<?php

namespace App\Http\Controllers\General\Subjects;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;

use App\Models\Clients\GeneralSubjects;
use App\Models\Clients\GeneralSubjectsLogs;
use App\Models\Log\LogEvents;
use App\Models\User;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;
use Illuminate\Http\Request;

class GeneralSubjectsListController extends Controller
{



    public function list_fl(Request $request)
    {

        if(!auth()->user()->hasPermission('subject', 'fl'))
        {
            abort(303);
        }


        return view("general.subjects.list.fl", [
            'type' => 0
        ]);

    }

    public function list_ul(Request $request)
    {

        if(!auth()->user()->hasPermission('subject', 'ul'))
        {
            abort(303);
        }


        return view("general.subjects.list.ul", [
            'type' => 1
        ]);

    }


    public function get_table($type, Request $request)
    {
        $generals = GeneralSubjects::getAllGeneralSubjects((int)$type, auth()->user(), 0);

        $view = 'fl';

        if((int)$type == 0){
            $view = 'fl';

            if(isset($request->birthdate) && strlen($request->birthdate) > 9){
                $generals->leftJoin('general_subjects_fl', 'general_subjects_fl.general_subject_id', '=', 'general_subjects.id');
                $generals->where('general_subjects_fl.birthdate',  getDateFormatEn($request->birthdate));
                $generals->select("general_subjects.*");


            }

        }

        if((int)$type == 1){
            $view = 'ul';
        }
        $like = (env('DB_CONNECTION') == "pgsql") ? 'ilike' : 'like';
        if(isset($request->title) && strlen($request->title) > 3){
            $generals->where('general_subjects.title', $like, "%{$request->title}%");
        }


        $page = request()->get('PAGE') > 0 ? (int)request()->get('PAGE') : 1;
        $page_count = request()->get('page_count') > 0 ? (int)request()->get('page_count') : 10;
        $result = PaginationHelper::paginate($generals, $page, $page_count);


        $generals = $result['builder']->get();

        $html = view("general.subjects.list.table.{$view}", [
            'generals' => $generals,
        ])->render();

        return [
            'html' => $html,
            'page_max' => $result['page_max'],
            'page_sel' => $result['page_sel'],
            'max_row' => $result['max_row'],
            'view_row' => $result['view_row'],
        ];

    }


}
