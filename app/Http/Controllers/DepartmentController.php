<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\BaseModel;
use App\Models\Team;
use App\Http\Requests\Team\StoreDepartment;
use App\Models\EmployeeDetails;

class DepartmentController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.teams';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('employees', $this->user->modules));
            return $next($request);
        });
    }

    public function create()
    {
        $this->departments = Team::allDepartments();
        return view('department.create', $this->data);
    }

    /**
     * @param StoreDepartment $request
     * @return array
     * @throws \Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException
     */
    public function store(StoreDepartment $request)
    {
        $group = new Team();
        $group->team_name = $request->team_name;
        $group->save();

        $teams = Team::allDepartments();

        $options = BaseModel::options($teams, $group, 'team_name');

        return Reply::successWithData(__('messages.departmentAdded'), ['data' => $options]);
    }

    /**
     * @param StoreDepartment $request
     * @param int $id
     * @return array
     * @throws \Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException
     */
    public function update(StoreDepartment $request, $id)
    {
        $editDepartment = user()->permission('edit_department');

        abort_403($editDepartment != 'all');

        $group = Team::find($id);
        $group->team_name = strip_tags($request->team_name);
        $group->save();

        $teams = Team::allDepartments();
        $options = BaseModel::options($teams, null, 'team_name');

        return Reply::successWithData(__('messages.updatedSuccessfully'), ['data' => $options]);
    }

    public function destroy($id)
    {
        EmployeeDetails::where('department_id', $id)->update(['department_id' => null]);
        Team::destroy($id);

        $teams = Team::allDepartments();
        $options = BaseModel::options($teams, null, 'team_name');

        return Reply::successWithData(__('messages.deleteSuccess'), ['data' => $options]);
    }

}
