<?php

namespace App\DataTables;

use Carbon\Carbon;
use App\Models\Project;
use App\DataTables\BaseDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;

class ProjectsDataTable extends BaseDataTable
{

    private $editProjectsPermission;
    private $deleteProjectPermission;
    private $viewProjectPermission;
    private $viewGanttPermission;

    public function __construct()
    {
        parent::__construct();
        $this->editProjectsPermission = user()->permission('edit_projects');
        $this->deleteProjectPermission = user()->permission('delete_projects');
        $this->viewProjectPermission = user()->permission('view_projects');
        $this->viewGanttPermission = user()->permission('view_project_gantt_chart');
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="select-table-row" id="datatable-row-' . $row->id . '"  name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">';
            })
            ->addColumn('action', function ($row) {
                $memberIds = $row->members->pluck('user_id')->toArray();

                $action = '<div class="task_view">

                <div class="dropdown">
                    <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" type="link"
                        id="dropdownMenuLink-' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-options-vertical icons"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-' . $row->id . '" tabindex="0">';

                $action .= '<a href="' . route('projects.show', [$row->id]) . '" class="dropdown-item"><i class="fa fa-eye mr-2"></i>' . __('app.view') . '</a>';

                if (
                    $this->editProjectsPermission == 'all'
                    || $row->public
                    || ($this->editProjectsPermission == 'added' && user()->id == $row->added_by)
                    || ($this->editProjectsPermission == 'owned' && user()->id == $row->client_id && in_array('client', user_roles()))
                    || ($this->editProjectsPermission == 'owned' && in_array(user()->id, $memberIds) && in_array('employee', user_roles()))
                    || ($this->editProjectsPermission == 'both' && (user()->id == $row->client_id || user()->id == $row->added_by))
                    || ($this->editProjectsPermission == 'both' && in_array(user()->id, $memberIds) && in_array('employee', user_roles()))
                ) {
                    $action .= '<a class="dropdown-item openRightModal" href="' . route('projects.edit', [$row->id]) . '">
                            <i class="fa fa-edit mr-2"></i>
                            ' . trans('app.edit') . '
                        </a>';
                }

                if ($this->viewGanttPermission == 'all' || ($this->viewGanttPermission == 'added' && user()->id == $row->added_by) || ($this->viewGanttPermission == 'owned' && user()->id == $row->client_id)) {
                    $action .= '<a class="dropdown-item" href="' . route('projects.show', $row->id) . '?tab=gantt' . '">
                            <i class="fa fa-project-diagram mr-2"></i>
                            ' . trans('modules.projects.viewGanttChart') . '
                        </a>';
                }

                $action .= '<a class="dropdown-item" target="_blank" href="' . route('front.gantt', $row->hash) . '">
                    <i class="fa fa-share-square mr-2"></i>
                    ' . trans('modules.projects.viewPublicGanttChart') . '
                </a>';

                $action .= '<a class="dropdown-item" target="_blank" href="' . route('front.taskboard', $row->hash) . '">
                    <i class="fa fa-share-square mr-2"></i>
                    ' . trans('app.public') . ' ' . __('modules.tasks.taskBoard') .'
                </a>';

                if (
                    $this->deleteProjectPermission == 'all'
                    || ($this->deleteProjectPermission == 'added' && user()->id == $row->added_by)
                    || ($this->deleteProjectPermission == 'owned' && user()->id == $row->client_id && in_array('client', user_roles()))
                    || ($this->deleteProjectPermission == 'owned' && in_array(user()->id, $memberIds) && in_array('employee', user_roles()))
                    || ($this->deleteProjectPermission == 'both' && (user()->id == $row->client_id || user()->id == $row->added_by))
                    || ($this->deleteProjectPermission == 'both' && in_array(user()->id, $memberIds) && in_array('employee', user_roles()))
                ){
                    $action .= '<a class="dropdown-item archive" href="javascript:;" data-user-id="' . $row->id . '">
                            <i class="fa fa-archive mr-2"></i>
                            ' . trans('app.archive') . '
                        </a>';
                    $action .= '<a class="dropdown-item delete-table-row" href="javascript:;" data-user-id="' . $row->id . '">
                            <i class="fa fa-trash mr-2"></i>
                            ' . trans('app.delete') . '
                        </a>';
                }

                $action .= '</div>
                </div>
            </div>';

                return $action;
            })
            ->addColumn('members', function ($row) {
                if ($row->public) {
                    return '--';
                }

                $members = '<div class="position-relative">';

                if (count($row->members) > 0) {
                    foreach ($row->members as $key => $member) {
                        if ($key < 4) {
                            $img = '<img data-toggle="tooltip" data-original-title="' . ucwords($member->user->name) . '" src="' . $member->user->image_url . '">';

                            $position = $key > 0 ? 'position-absolute' : '';
                            $members .= '<div class="taskEmployeeImg rounded-circle '.$position.'" style="left:  '. ($key * 13) . 'px"><a href="' . route('employees.show', $member->user->id) . '">' . $img . '</a></div> ';
                        }

                    }
                }
                else {
                    $members .= '<a href="' . route('projects.show', $row->id) . '?tab=members" class="f-12 text-dark-grey"><i class="fa fa-plus" ></i> '.__('modules.projects.addMemberTitle').'</a>';
                }

                if (count($row->members) > 4) {
                    $members .= '<div class="taskEmployeeImg more-user-count text-center rounded-circle bg-amt-grey position-absolute" style="left:  52px"><a href="' . route('projects.show', $row->id) . '?tab=members" class="text-dark f-10">+' . (count($row->members) - 4) . '</a></div> ';
                }

                $members .= '</div>';

                return $members;
            })
            ->addColumn('name', function ($row) {
                $members = [];

                if (count($row->members) > 0) {

                    foreach ($row->members as $member) {
                        $members[] = $member->user->name;
                    }

                    return implode(',', $members);
                }
            })
            ->addColumn('project', function ($row) {
                return ucfirst($row->project_name);
            })
            ->editColumn('project_name', function ($row) {
                $pin = '';

                if (($row->pinned_project)) {
                    $pin .= '<span class="badge badge-secondary"><i class="fa fa-thumbtack"></i> ' . __('app.pinned') . '</span>';
                }

                if (($row->public)) {
                    $pin = '<span class="badge badge-primary"><i class="fa fa-globe"></i> ' . __('app.public') . '</span>';
                }

                return '<div class="media align-items-center">
                        <div class="media-body">
                    <h5 class="mb-0 f-13 text-darkest-grey"><a href="' . route('projects.show', [$row->id]) . '">' . ucfirst($row->project_name) . '</a></h5>
                    <p class="mb-0">' . $pin . '</p>
                    </div>
                </div>';
            })
            ->editColumn('start_date', function ($row) {
                return $row->start_date->format($this->global->date_format);
            })
            ->editColumn('deadline', function ($row) {
                if ($row->deadline) {
                    return $row->deadline->format($this->global->date_format);
                }

                return '--';
            })
            ->addColumn('client_name', function ($row) {
                if (!is_null($row->client_id)) {
                    return $row->client->name;
                }
            })
            ->addColumn('client_email', function ($row) {
                if (!is_null($row->client_id)) {
                    return $row->client->email;
                }
            })
            ->editColumn('client_id', function ($row) {
                if (is_null($row->client_id)) {
                    return '';
                }

                return view('components.client', [
                    'user' => $row->client
                ]);
            })
            ->editColumn('status', function ($row) {

                if ($row->status == 'in progress') {
                    return view('components.status', [
                        'color' => 'blue',
                        'value' => __('app.inProgress')
                    ]);
                }
                elseif ($row->status == 'on hold') {
                    return ' <i class="fa fa-circle mr-1 text-yellow f-10"></i>' . __('app.onHold');
                }
                elseif ($row->status == 'not started') {
                    return ' <i class="fa fa-circle mr-1 text-dark-grey f-10"></i>' . __('app.notStarted');
                }
                elseif ($row->status == 'canceled') {
                    return ' <i class="fa fa-circle mr-1 text-red f-10"></i>' . __('app.canceled');
                }
                elseif ($row->status == 'finished') {
                    return ' <i class="fa fa-circle mr-1 text-dark-green f-10"></i>' . __('app.finished');
                }
            })
            ->editColumn('completion_percent', function ($row) {
                if ($row->completion_percent < 50) {
                    $statusColor = 'danger';
                }
                elseif ($row->completion_percent >= 50 && $row->completion_percent < 75) {
                    $statusColor = 'warning';
                }
                else {
                    $statusColor = 'success';
                }

                return '<div class="progress" style="height: 15px;">
                <div class="progress-bar f-12 bg-' . $statusColor . '" role="progressbar" style="width: ' . $row->completion_percent . '%;" aria-valuenow="' . $row->completion_percent . '" aria-valuemin="0" aria-valuemax="100">' . $row->completion_percent . '%</div>
              </div>';
            })
            ->addColumn('completion_export', function ($row) {
                return $row->completion_percent . '% ' . __('app.complete');
            })
            ->addIndexColumn()
            ->setRowId(function ($row) {
                return 'row-' . $row->id;
            })
            ->rawColumns(['project_name', 'action', 'completion_percent', 'members', 'status', 'client_id', 'check'])
            ->removeColumn('project_summary')
            ->removeColumn('notes')
            ->removeColumn('category_id')
            ->removeColumn('feedback');
    }

    /**
     * @param Project $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Project $model)
    {
        $request = $this->request();

        $model = $model
            ->with('members', 'members.user', 'client', 'client.clientDetails', 'currency', 'client.session')
            ->leftJoin('project_members', 'project_members.project_id', 'projects.id')
            ->leftJoin('users', 'project_members.user_id', 'users.id')
            ->leftJoin('users as client', 'projects.client_id', 'users.id')
            ->selectRaw('projects.id, projects.hash, projects.added_by, projects.project_name, projects.start_date, projects.deadline, projects.client_id,
              projects.completion_percent, projects.project_budget, projects.currency_id,
            projects.status, users.name, client.name as client_name, client.email as client_email, projects.public,
           ( select count("id") from pinned where pinned.project_id = projects.id and pinned.user_id = ' . user()->id . ') as pinned_project');


        if ($request->pinned == 'pinned') {
            $model->join('pinned', 'pinned.project_id', 'projects.id');
            $model->where('pinned.user_id', user()->id);
        }

        if (!is_null($request->status) && $request->status != 'all') {
            if ($request->status == 'not finished') {
                $model->where('projects.status', '<>', 'finished');
            }
            else if ($request->status == 'overdue') {
                $model->where('projects.status', '<>', 'finished');
                $model->where('projects.status', '<>', 'canceled');

                if ($request->deadLineStartDate == '' && $request->deadLineEndDate == '') {
                    $model->whereDate('projects.deadline', '<', now(global_setting()->timezone)->toDateString());
                }
            }
            else {
                $model->where('projects.status', $request->status);
            }
        }

        if ($request->deadLineStartDate != '' && $request->deadLineEndDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->deadLineStartDate)->toDateString();
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->deadLineEndDate)->toDateString();
            $model->whereRaw('Date(projects.deadline) >= ?', [$startDate])->whereRaw('Date(projects.deadline) <= ?', [$endDate]);
        }

        if (!is_null($request->client_id) && $request->client_id != 'all') {
            $model->where('projects.client_id', $request->client_id);
        }

        if (!is_null($request->team_id) && $request->team_id != 'all') {
            $model->where('team_id', $request->team_id);
        }

        if (!is_null($request->category_id) && $request->category_id != 'all') {
            $model->where('category_id', $request->category_id);
        }


        if (!is_null($request->employee_id) && $request->employee_id != 'all') {
            $model->where('project_members.user_id', $request->employee_id);
        }

        if ($this->viewProjectPermission == 'added') {
            $model->where(function ($query) {
                return $query->where('projects.added_by', user()->id)
                    ->orWhere('projects.public', 1);
            });
        }

        if ($this->viewProjectPermission == 'owned' && in_array('employee', user_roles())) {
            $model->where(function ($query) {
                return $query->where('project_members.user_id', user()->id)
                    ->orWhere('projects.public', 1);
            });
        }

        if ($this->viewProjectPermission == 'both' && in_array('employee', user_roles())) {
            $model->where(function ($query) {
                return $query->where('projects.added_by', user()->id)
                    ->orWhere('project_members.user_id', user()->id)
                    ->orWhere('projects.public', 1);
            });
        }

        if ($request->searchText != '') {
            $model->where(function ($query) {
                $query->where('projects.project_name', 'like', '%' . request('searchText') . '%')
                    ->orWhere('users.name', 'like', '%' . request('searchText') . '%');
            });
        }

        if ($request->startDate && $request->endDate) {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $model->whereBetween(DB::raw('DATE(projects.`created_at`)'), [$startDate, $endDate]);
        }

        $model->groupBy('projects.id');

        return $model;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('projects-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(2)
            ->destroy(true)
            ->responsive(true)
            ->serverSide(true)
            ->processing(true)
            ->language(__('app.datatable'))
            ->parameters([
                'initComplete' => 'function () {
                    window.LaravelDataTables["projects-table"].buttons().container()
                     .appendTo( "#table-actions")
                 }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    })
                }',
            ])
            ->buttons(Button::make(['extend' => 'excel', 'text' => '<i class="fa fa-file-export"></i> ' . trans('app.exportExcel')]));
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'check' => [
                'title' => '<input type="checkbox" name="select_all_table" id="select-all-table" onclick="selectAllTable(this)">',
                'exportable' => false,
                'orderable' => false,
                'searchable' => false,
                'visible' => !in_array('client', user_roles())
            ],
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'visible' => false],
            __('app.id') => ['data' => 'id', 'name' => 'id', 'title' => __('app.id')],
            __('modules.projects.projectName') => ['data' => 'project_name', 'name' => 'project_name', 'exportable' => false, 'title' => __('modules.projects.projectName')],
            __('app.project') => ['data' => 'project', 'name' => 'project_name', 'visible' => false, 'title' => __('app.project')],
            __('modules.projects.members')  => ['data' => 'members', 'name' => 'members', 'exportable' => false, 'width' => '15%', 'title' => __('modules.projects.members')],
            __('modules.projects.projectMembers')  => ['data' => 'name', 'name' => 'name', 'visible' => false, 'title' => __('modules.projects.projectMembers')],
            __('modules.projects.startDate')  => ['data' => 'start_date', 'name' => 'start_date', 'visible' => false, 'title' => __('modules.projects.startDate')],
            __('app.deadline') => ['data' => 'deadline', 'name' => 'deadline', 'title' => __('app.deadline')],
            __('app.client') => ['data' => 'client_id', 'name' => 'client_id', 'width' => '15%', 'exportable' => false, 'title' => __('app.client')],
            __('app.customers')  => ['data' => 'client_name', 'name' => 'client_id', 'visible' => false, 'title' => __('app.customers')],

            __('app.client') . ' ' . __('app.email')  => ['data' => 'client_email', 'name' => 'client_id', 'visible' => false, 'title' => __('app.client') . ' ' . __('app.email')],
            __('app.progress') => ['data' => 'completion_percent', 'name' => 'completion_percent', 'exportable' => false, 'title' => __('app.progress')],
            __('app.completion') => ['data' => 'completion_export', 'name' => 'completion_export', 'visible' => false, 'title' => __('app.completion')],
            __('app.status') => ['data' => 'status', 'name' => 'status', 'width' => '16%', 'title' => __('app.status')],
            Column::computed('action', __('app.action'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->addClass('text-right pr-20')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Projects_' . date('YmdHis');
    }

    public function pdf()
    {
        set_time_limit(0);

        if ('snappy' == config('datatables-buttons.pdf_generator', 'snappy')) {
            return $this->snappyPdf();
        }

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('datatables::print', ['data' => $this->getDataForPrint()]);

        return $pdf->download($this->getFilename() . '.pdf');
    }

}
