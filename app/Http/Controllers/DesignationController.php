<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Http\Requests\Designation\StoreRequest;
use App\Http\Requests\Designation\UpdateRequest;
use App\Models\BaseModel;
use App\Models\Designation;
use App\Models\EmployeeDetails;

class DesignationController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.designation';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('employees', $this->user->modules));
            return $next($request);
        });
    }

    public function create()
    {
        $this->designations = Designation::allDesignations();
        return view('designation.create', $this->data);
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        $group = new Designation();
        $group->name = $request->name;
        $group->save();

        $designations = Designation::allDesignations();

        $options = BaseModel::options($designations, $group);

        return Reply::successWithData(__('messages.designationAdded'), ['data' => $options]);
    }

    /**
     * @param UpdateRequest $request
     * @param int $id
     * @return array
     * @throws \Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException
     */
    public function update(UpdateRequest $request, $id)
    {
        $editDesignation = user()->permission('edit_designation');
        abort_403($editDesignation != 'all');

        $group = Designation::find($id);
        $group->name = strip_tags($request->designation_name);
        $group->save();

        $designations = Designation::allDesignations();
        $options = BaseModel::options($designations);

        return Reply::successWithData(__('messages.updatedSuccessfully'), ['data' => $options]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        EmployeeDetails::where('designation_id', $id)->update(['designation_id' => null]);
        Designation::destroy($id);

        $designations = Designation::allDesignations();
        $options = BaseModel::options($designations);

        return Reply::successWithData(__('messages.deleteSuccess'), ['data' => $options]);
    }

}
