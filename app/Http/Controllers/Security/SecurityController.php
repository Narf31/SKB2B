<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\Security\Security;
use Illuminate\Http\Request;

class SecurityController extends Controller
{

    public function __construct()
    {
        //$this->middleware('permissions:security, security_inquiry');
    }

    public function security_inquiry()
    {

        return view('security.security_inquiry', [
            'inquirys' => Security::where('status', '=', Security::STATUS_SEND)
                ->orderBy('created_at', 'asc')
                ->get()
        ]);
    }

    public function create(Request $request){

        $sec = new SendSecurity();
        $sec->createSecurityInquiry($request->types, $request->id, auth()->user()->getAuthIdentifier());

        return response(1, 200);
    }

    public function showComments($id){
        $inquiry = Security::findOrFail($id);
        return view('security.show_comment', [
            'inquiry' => $inquiry,
        ]);
    }


    public function security_work()
    {
        $inquirys = Security::query();
        $inquirys->where('status', '=', Security::STATUS_WORK);
        if(!auth()->user()->hasPermission('security', 'security_archive_all')){
            $inquirys->where('work_user_id', '=', auth()->user()->getAuthIdentifier());
        }

        return view('security.security_work', [
            'inquirys' => $inquirys->orderBy('created_at', 'asc')->get()
        ]);
    }

    public function security_archive(Request $request)
    {
        $inquirys = Security::query();
        $inquirys->where('status', '=', Security::STATUS_ARCHIVE);
        if(!auth()->user()->hasPermission('security', 'security_archive_all')){
            $inquirys->where('work_user_id', '=', auth()->user()->getAuthIdentifier());
        }

        $dates_start = date('d.m.Y');
        $dates_end = date('d.m.Y');

        if (strtotime($request->date_from) > 0) $dates_start = $request->date_from;
        if (strtotime($request->date_to) > 0) $dates_end = $request->date_to;

        $inquirys->where('dates_work', '>=', getDateFormat($dates_start)." 00:00:00");
        $inquirys->where('dates_work', '<=', getDateFormat($dates_end)." 23:59:59");

        return view('security.security_archive', [
            'inquirys' => $inquirys->orderBy('created_at', 'asc')->get(),
            'dates_start'          => $dates_start,
            'dates_end'          => $dates_end,
        ]);
    }

    public function work($id){

        $inquiry = Security::findOrFail($id);

        if($inquiry->status == Security::STATUS_SEND){
            $inquiry->dates_work = getDateTime();
            $inquiry->work_user_id = auth()->user()->getAuthIdentifier();
            $inquiry->status = Security::STATUS_WORK;
            $inquiry->save();
        }

        if($inquiry->status == Security::STATUS_WORK){
            if($inquiry->work_user_id!=auth()->user()->getAuthIdentifier() && !auth()->user()->hasPermission('security', 'security_archive_all')){
                return view("errors.403", ['exception' => '1']);
            }
        }

        return view('security.work', [
            'inquiry' => $inquiry,
        ]);

    }


    public function send($id, Request $request){

        $inquiry = Security::findOrFail($id);

        if($request->status == 'returns'){
            $inquiry->dates_work = null;
            $inquiry->work_user_id = 0;
            $inquiry->status = Security::STATUS_SEND;
            $inquiry->save();
            return redirect(url("/security/security_inquiry"));
        }

        $inquiry->comments = $request->comments;
        $inquiry->save();

        if($request->status == 'archive'){
            $inquiry->status = Security::STATUS_ARCHIVE;
            $inquiry->save();
            return redirect(url("/security/security_inquiry"));
        }else{
            return redirect(url("/security/$id/work"));
        }

    }



}
