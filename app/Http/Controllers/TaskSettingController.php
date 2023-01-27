<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Http\Requests\CommonRequest;
use App\Models\TaskboardColumn;

class TaskSettingController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.taskSettings';
        $this->activeSettingMenu = 'task_settings';
        $this->middleware(function ($request, $next) {
            abort_403(!(user()->permission('manage_task_setting') == 'all'));
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->companyData = $this->global;
        $this->taskboardColumns = TaskboardColumn::orderBy('priority', 'asc')->get();

        return view('task-settings.index', $this->data);
    }

    /**
     * @param CommonRequest $request
     * @return array
     */
    public function store(CommonRequest $request)
    {
        $company = $this->global;

        $company->before_days = $request->before_days;
        $company->after_days = $request->after_days;
        $company->on_deadline = $request->on_deadline;
        $company->default_task_status = $request->default_task_status;
        $company->taskboard_length = $request->taskboard_length;
        $company->save();
        session()->forget('global_setting');

        return Reply::success(__('messages.settingsUpdated'));
    }

}
