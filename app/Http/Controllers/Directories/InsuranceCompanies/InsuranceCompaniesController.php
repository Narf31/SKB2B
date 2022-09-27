<?php

namespace App\Http\Controllers\Directories\InsuranceCompanies;

use App\Http\Controllers\Controller;
use App\Models\Directories\InsuranceCompanies;
use App\Models\Log\LogEvents;
use App\Repositories\FilesRepository;
use Illuminate\Http\Request;

class InsuranceCompaniesController extends Controller
{

    public function __construct()
    {
        $this->middleware('permissions:directories,insurance_companies');
        
        $this->breadcrumbs[] = [
            'label' => 'Справочники',
        ];
        
        $this->breadcrumbs[] = [
            'label' => 'Страховые компании',
            'url' => 'directories/insurance_companies',
        ];
    }

    public function index()
    {
        return $this->edit(InsuranceCompanies::first()->id);

        return view('directories.insurance_companies.index', [
            'insurance_companies' => InsuranceCompanies::orderBy('title')->get()
        ]);
    }

    public function edit($id)
    {

        if((int)$id > 0){
            $sk = InsuranceCompanies::where('id', $id)->get()->first();
            $this->breadcrumbs[] = [
             'label' => $sk->title,
            ];
        }else{
            $sk = new InsuranceCompanies();
            $this->breadcrumbs[] = [
             'label' => 'Новая СК',
            ];
        }


        return view('directories.insurance_companies.edit', [
            'insurance_companies' => $sk
        ])->with('breadcrumbs', $this->breadcrumbs);

    }


    public function save($id, Request $request)
    {
        $sk = new InsuranceCompanies();
        if((int)$id > 0){
            $sk = InsuranceCompanies::where('id', $id)->get()->first();
            LogEvents::event($sk->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE], 11, $sk->id,0, $request->all());

        }else{
            $sk->save();
            LogEvents::event($sk->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE], 11, $sk->id,0, $request->all());

        }


        $sk->title = $request->title;
        $sk->is_actual = (int)$request->is_actual;
        $sk->save();

        if ($request->hasFile('logo')) {
            $repository = new FilesRepository;

            $file = $repository->makeFile($request->logo, InsuranceCompanies::FILES_FOLDER);
            $sk->logo_id = $file->id;
            $sk->save();

            LogEvents::event($sk->id, "Загрузка логотипа {$file->original_name}", 11, $sk->id);


        }

        return redirect("/directories/insurance_companies/$sk->id/")->with('success', 'Данные успешно сохранены');



    }


}
