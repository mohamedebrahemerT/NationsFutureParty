<?php

namespace App\Http\Controllers;

use App\DataTables\LeaveDataTable;
use App\Helper\Reply;
use App\Http\Requests\Leaves\StoreLeave;
use App\Http\Requests\Leaves\UpdateLeave;
use App\Models\EmployeeLeaveQuota;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.leaves';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('leaves', $this->user->modules));
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(LeaveDataTable $dataTable)
    {
        $viewPermission = user()->permission('view_leave');
        abort_403(!in_array($viewPermission, ['all', 'added', 'owned', 'both']));

        $this->employees = User::allEmployees(null, null, ($viewPermission == 'all' ? 'all' : null));
        $this->leaveTypes = LeaveType::all();

        return $dataTable->render('leaves.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->addPermission = user()->permission('add_leave');
        abort_403(!in_array($this->addPermission, ['all', 'added']));

        $this->employees = User::allEmployees();

        if ($this->addPermission == 'added') {
            $this->defaultAssign = User::with('leaveTypes', 'leaveTypes.leaveType')->findOrFail(user()->id);
            $this->leaveQuotas = $this->defaultAssign->leaveTypes;

        } else if(isset(request()->default_assign)) {
            $this->defaultAssign = User::with('leaveTypes', 'leaveTypes.leaveType')->findOrFail(request()->default_assign);
            $this->leaveQuotas = $this->defaultAssign->leaveTypes;
        
        } else {
            $this->leaveTypes = LeaveType::all();
        }

        if (request()->ajax()) {
            $this->pageTitle = __('modules.leaves.addLeave');
            $html = view('leaves.ajax.create', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'leaves.ajax.create';

        return view('leaves.create', $this->data);
    }

    /**
     * @param StoreLeave $request
     * @return array|void
     * @throws \Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException
     */
    public function store(StoreLeave $request)
    {
        $this->addPermission = user()->permission('add_leave');
        abort_403(!in_array($this->addPermission, ['all', 'added']));

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('leaves.index');
        }

        $leaveQuota = EmployeeLeaveQuota::where('user_id', $request->user_id)->where('leave_type_id', $request->leave_type_id)->first();

        $leaveTaken = LeaveType::byUser($request->user_id, $request->leave_type_id);

        if ($request->duration == 'multiple') {
            session(['leaves_duration' => 'multiple']);

            $dates = explode(',', $request->multi_date);
            $multiDates = [];

            foreach($dates as $dateData)
            {
                array_push($multiDates, Carbon::parse($dateData)->format('Y-m-d'));
            }
            
            if (($leaveTaken[0]->leavesCount[0]->count + count($multiDates)) >= $leaveQuota->no_of_leaves) {
                return Reply::error(__('messages.leaveLimitError'));
            }

            $leaveApplied = Leave::select(DB::raw('DATE_FORMAT(leave_date, "%Y-%m-%d") as leave_date_new'))
                ->where('user_id', $request->user_id)
                ->whereIn('leave_date', $multiDates)
                ->pluck('leave_date_new')
                ->toArray();
           
            $holidays = Holiday::select(DB::raw('DATE_FORMAT(date, "%Y-%m-%d") as holiday_date'))
                ->whereIn('date', $multiDates)
                ->pluck('holiday_date')->toArray();

            foreach ($dates as $date) {
                $dateInsert = Carbon::parse($date)->format('Y-m-d');

                if(!in_array($dateInsert, $leaveApplied) && !in_array($dateInsert, $holidays)){
                    $leave = new Leave();
                    $leave->user_id = $request->user_id;
                    $leave->leave_type_id = $request->leave_type_id;
                    $leave->duration = $request->duration;
                    $leave->leave_date = $dateInsert;
                    $leave->reason = $request->reason;
                    $leave->status = ($request->has('status') ? $request->status : 'pending');
                    $leave->save();
                    session()->forget('leaves_duration');
                }
            }

            return Reply::successWithData(__('messages.leaveApplySuccess'), ['redirectUrl' => $redirectUrl]);
        }
        else {
            $dateInsert = Carbon::createFromFormat($this->global->date_format, $request->leave_date)->format('Y-m-d');

            if ((isset($leaveTaken[0]->leavesCount[0]) && $leaveTaken[0]->leavesCount[0]->count + 1) >= $leaveQuota->no_of_leaves) {
                return Reply::error(__('messages.leaveLimitError'));
            }

            $leaveApplied = Leave::where('user_id', $request->user_id)->whereDate('leave_date', $dateInsert)->first();
            $holiday = Holiday::select(DB::raw('DATE_FORMAT(date, "%Y-%m-%d") as holiday_date'))->where('date', $dateInsert)->first();

            if(!is_null($leaveApplied)){
                return Reply::error(__('messages.leaveApplyError'));
            }

            if(!is_null($holiday)){
                return Reply::error(__('messages.holidayLeaveApplyError'));
            }

            $leave = new Leave();
            $leave->user_id = $request->user_id;
            $leave->leave_type_id = $request->leave_type_id;
            $leave->duration = $request->duration;
            $leave->leave_date = Carbon::createFromFormat($this->global->date_format, $request->leave_date)->format('Y-m-d');
            $leave->reason = $request->reason;
            $leave->status = ($request->has('status') ? $request->status : 'pending');
            $leave->save();

            return Reply::successWithData(__('messages.leaveApplySuccess'), ['redirectUrl' => $redirectUrl]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->leave = Leave::findOrFail($id);
        $this->viewPermission = user()->permission('view_leave');
        abort_403(!($this->viewPermission == 'all'
        || ($this->viewPermission == 'added' && user()->id == $this->leave->added_by)
        || ($this->viewPermission == 'owned' && user()->id == $this->leave->user_id)
        || ($this->viewPermission == 'both' && (user()->id == $this->leave->user_id || user()->id == $this->leave->added_by))
        ));

        $this->pageTitle = $this->leave->user->name;

        if (request()->ajax()) {
            $html = view('leaves.ajax.show', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'leaves.ajax.show';

        return view('leaves.create', $this->data);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->leave = Leave::findOrFail($id);
        $this->editPermission = user()->permission('edit_leave');
        abort_403(!(
            ($this->editPermission == 'all'
            || ($this->editPermission == 'added' && $this->leave->added_by == user()->id)
            || ($this->editPermission == 'owned' && $this->leave->user_id == user()->id)
            || ($this->editPermission == 'both' && ($this->leave->user_id == user()->id || $this->leave->added_by == user()->id))
            )
        && ($this->leave->status == 'pending')));

        $this->employees = User::allEmployees();
        $this->leaveTypes = LeaveType::all();

        $this->pageTitle = $this->leave->user->name;

        if ($this->editPermission == 'added') {
            $this->defaultAssign = user();

        } else if(isset(request()->default_assign)) {
            $this->defaultAssign = User::findOrFail(request()->default_assign);
        }

        if (request()->ajax()) {
            $html = view('leaves.ajax.edit', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'leaves.ajax.edit';

        return view('leaves.create', $this->data);

    }

    /**
     * @param UpdateLeave $request
     * @param int $id
     * @return array|void
     * @throws \Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException
     */
    public function update(UpdateLeave $request, $id)
    {
        $leave = Leave::findOrFail($id);
        $this->editPermission = user()->permission('edit_leave');

        abort_403(!($this->editPermission == 'all'
        || ($this->editPermission == 'added' && $leave->added_by == user()->id)
        || ($this->editPermission == 'owned' && $leave->user_id == user()->id)
        || ($this->editPermission == 'both' && ($leave->user_id == user()->id || $leave->added_by == user()->id))
        ));

        $leave->user_id = $request->user_id;
        $leave->leave_type_id = $request->leave_type_id;
        $leave->leave_date = Carbon::createFromFormat($this->global->date_format, $request->leave_date)->format('Y-m-d');
        $leave->reason = $request->reason;

        if ($request->has('reject_reason')) {
            $leave->reject_reason = $request->reject_reason;
        }

        if ($request->has('status')) {
            $leave->status = $request->status;
        }

        $leave->save();

        return Reply::successWithData(__('messages.leaveAssignSuccess'), ['redirectUrl' => route('leaves.index')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $leave = Leave::findOrFail($id);
        $this->deletePermission = user()->permission('delete_leave');

        abort_403(!($this->deletePermission == 'all'
        || ($this->deletePermission == 'added' && $leave->added_by == user()->id)
        || ($this->deletePermission == 'owned' && $leave->user_id == user()->id)
        || ($this->deletePermission == 'both' && ($leave->user_id == user()->id || $leave->added_by == user()->id))
        ));

        Leave::destroy($id);
        return Reply::successWithData(__('messages.leaveDeleteSuccess'), ['redirectUrl' => route('leaves.index')]);
    }

    public function leaveCalendar(Request $request)
    {
        $viewPermission = user()->permission('view_leave');
        abort_403(!in_array($viewPermission, ['all', 'added', 'owned', 'both']));

        $this->pendingLeaves = Leave::where('status', 'pending')->count();
        $this->employees = User::allEmployees();
        $this->leaveTypes = LeaveType::all();
        $this->pageTitle = 'app.menu.calendar';

        if (request('start') && request('end')) {

            $leaveArray = array();

            $leavesList = Leave::join('users', 'users.id', 'leaves.user_id')
                ->join('leave_types', 'leave_types.id', 'leaves.leave_type_id')
                ->where('users.status', 'active')
                ->where('leaves.status', '<>', 'rejected')
                ->select('leaves.id', 'users.name', 'leaves.leave_date', 'leaves.status', 'leave_types.type_name', 'leave_types.color', 'leaves.leave_date', 'leaves.duration', 'leaves.status');

            if (!is_null($request->startDate)) {
                $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
                $leavesList->whereRaw('Date(leaves.leave_date) >= ?', [$startDate]);
            }

            if (!is_null($request->endDate)) {
                $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();

                $leavesList->whereRaw('Date(leaves.leave_date) <= ?', [$endDate]);
            }

            if ($request->employeeId != 'all' && $request->employeeId != '') {
                $leavesList->where('users.id', $request->employeeId);
            }

            if ($request->leaveTypeId != 'all' && $request->leaveTypeId != '') {
                $leavesList->where('leave_types.id', $request->leaveTypeId);
            }

            if ($request->status != 'all' && $request->status != '') {
                $leavesList->where('leaves.status', $request->status);
            }

            if ($request->searchText != '') {
                $leavesList->where('users.name', 'like', '%' . $request->searchText . '%');
            }

            if ($viewPermission == 'owned') {
                $leavesList->where('leaves.user_id', '=', user()->id);
            }
    
            if ($viewPermission == 'added') {
                $leavesList->where('leaves.added_by', '=', user()->id);
            }
    
            if ($viewPermission == 'both') {
                $leavesList->where(function ($q) {
                    $q->where('leaves.user_id', '=', user()->id);;
    
                    $q->orWhere('leaves.added_by', '=', user()->id);;
                });
            }
    

            $leaves = $leavesList->get();

            foreach ($leaves as $key => $leave) {
                /** @phpstan-ignore-next-line */
                $title = ucfirst($leave->name);

                $leaveArray[] = [
                    'id' => $leave->id,
                    'title' => $title,
                    'start' => $leave->leave_date->format('Y-m-d'),
                    'end' => $leave->leave_date->format('Y-m-d'),
                    /** @phpstan-ignore-next-line */
                    'color' => $leave->color
                ];
            }

            return $leaveArray;
        }

        return view('leaves.calendar.index', $this->data);
    }

    public function applyQuickAction(Request $request)
    {
        switch ($request->action_type) {
        case 'delete':
            $this->deleteRecords($request);
                return Reply::success(__('messages.deleteSuccess'));
        case 'change-leave-status':
            $this->changeBulkStatus($request);
                return Reply::success(__('messages.statusUpdatedSuccessfully'));
        default:
                return Reply::error(__('messages.selectAction'));
        }
    }

    protected function deleteRecords($request)
    {
        abort_403(user()->permission('delete_leave') != 'all');

        Leave::whereIn('id', explode(',', $request->row_ids))->delete();
    }

    protected function changeBulkStatus($request)
    {
        abort_403(user()->permission('edit_leave') != 'all');

        Leave::whereIn('id', explode(',', $request->row_ids))->update(['status' => $request->status]);
    }

    public function leaveAction(Request $request)
    {
        abort_403(user()->permission('approve_or_reject_leaves') == 'none');

        $leave = Leave::findOrFail($request->leaveId);
        $leave->status = $request->action;

        if (isset($request->reason)) {
            $leave->reject_reason = $request->reason;
        }

        $leave->save();

        return Reply::success(__('messages.leaveStatusUpdate'));
    }

    public function rejectLeave(Request $request)
    {
        abort_403(user()->permission('approve_or_reject_leaves') == 'none');

        $this->leaveAction = $request->leave_action;
        $this->leaveID = $request->leave_id;
        return view('leaves.reject.index', $this->data);
    }

    public function personalLeaves()
    {
        $this->pageTitle = __('modules.leaves.myLeaves');
        
        $this->employee = User::with(['employeeDetail', 'employeeDetail.designation', 'employeeDetail.department', 'leaveTypes', 'leaveTypes.leaveType', 'country', 'employee'])
            ->withoutGlobalScope('active')
            ->withCount('member', 'agents', 'tasks')
            ->findOrFail(user()->id);

        $this->leaveTypes = LeaveType::byUser(user()->id);
        $this->leavesTakenByUser = Leave::byUserCount(user()->id);
        $this->allowedLeaves = $this->employee->leaveTypes->sum('no_of_leaves');
        $this->employeeLeavesQuota = $this->employee->leaveTypes;
        $this->employeeLeavesQuotas = $this->employee->leaveTypes;
        $this->view = 'leaves.ajax.personal';

        return view('leaves.create', $this->data);

    }

}
