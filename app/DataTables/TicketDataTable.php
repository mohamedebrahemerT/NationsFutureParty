<?php

namespace App\DataTables;

use App\DataTables\BaseDataTable;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class   TicketDataTable extends BaseDataTable
{

    private $deleteTicketPermission;
    private $viewTicketPermission;

    public function __construct()
    {
        parent::__construct();
        $this->deleteTicketPermission = user()->permission('delete_tickets');
        $this->viewTicketPermission = user()->permission('view_tickets');
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return (new EloquentDataTable($query))
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="select-table-row" id="datatable-row-' . $row->id . '"  name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">';
            })
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $action = '<div class="task_view">';

                $action .= '<div class="dropdown">
                    <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" type="link"
                        id="dropdownMenuLink-' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-options-vertical icons"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-' . $row->id . '" tabindex="0">';

                if (
                        $this->viewTicketPermission == 'all'
                        || ($this->viewTicketPermission == 'added' && user()->id == $row->added_by)
                        || ($this->viewTicketPermission == 'owned' && (user()->id == $row->user_id || $row->agent_id == user()->id))
                        || ($this->viewTicketPermission == 'both' && (user()->id == $row->user_id || $row->agent_id == user()->id || $row->added_by == user()->id))
                        ) {
                    $action .= '<a href="' . route('tickets.show', [$row->id]) . '" class="dropdown-item"><i class="fa fa-eye mr-2"></i>' . __('app.view') . '</a>';
                }

                if (
                    $this->deleteTicketPermission == 'all'
                    || ($this->deleteTicketPermission == 'added' && user()->id == $row->added_by)
                    || ($this->deleteTicketPermission == 'owned' && (user()->id == $row->agent_id || user()->id == $row->user_id))
                    || ($this->deleteTicketPermission == 'both' && (user()->id == $row->agent_id || user()->id == $row->added_by || user()->id == $row->user_id))
                    ) {
                    $action .= '<a class="dropdown-item delete-table-row" href="javascript:;" data-ticket-id="' . $row->id . '">
                            <i class="fa fa-trash mr-2"></i>
                            ' . trans('app.delete') . '
                        </a>';
                }

                $action .= '</div>
                        </div>
                    </div>';

                return $action;
            })
            ->addColumn('others', function ($row) {
                $others = '';

                if (!is_null($row->agent)) {
                    $others .= '<div class="mb-2">' . __('modules.tickets.agent') . ': ' . (is_null($row->agent_id) ? '-' : ucwords($row->agent->name)) . '</div> ';
                }

                if ($row->status == 'open') {
                    $others .= '<div>' . __('app.status') . ': <label class="badge badge-danger">' . __('app.' . $row->status) . '</label></div> ';
                }
                elseif ($row->status == 'pending') {
                    $others .= '<div>' . __('app.status') . ': <label class="badge badge-warning">' . __('app.' . $row->status) . '</label></div> ';
                }
                elseif ($row->status == 'resolved') {
                    $others .= '<div>' . __('app.status') . ': <label class="badge badge-success">' . __('app.' . $row->status) . '</label></div> ';
                }
                elseif ($row->status == 'closed') {
                    $others .= '<div>' . __('app.status') . ': <label class="badge badge-primary">' . __('app.' . $row->status) . '</label></div> ';
                }
                $others .= '<div>' . __('modules.tasks.priority') . ': ' . __('app.' . $row->priority) . '</div> ';
                return $others;
            })
            ->editColumn('subject', function ($row) {
                return '<a href="' . route('tickets.show', $row->id) . '" class="text-darkest-grey" >' . ucfirst($row->subject) . '</a>';
            })
            ->addColumn('name', function ($row) {
                return $row->requester->name;
            })
            ->editColumn('user_id', function ($row) {
                if (is_null($row->requester)) {
                    return '';
                }

                if ($row->requester->hasRole('employee')) {
                    return view('components.employee', [
                        'user' => $row->requester
                    ]);
                }
                else {
                    return view('components.client', [
                        'user' => $row->requester
                    ]);
                }
            })
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at->timezone($this->global->timezone)->format($this->global->date_format . ' ' . $this->global->time_format);
            })
            ->setRowId(function ($row) {
                return 'row-' . $row->id;
            })
            ->orderColumn('user_id', 'name $1')
            ->rawColumns(['others', 'action', 'subject', 'check', 'user_id'])
            ->removeColumn('agent_id')
            ->removeColumn('channel_id')
            ->removeColumn('type_id')
            ->removeColumn('deleted_at');
    }

    /**
     * @param Ticket $model
     * @return Ticket
     */
    public function query(Ticket $model)
    {
        $request = $this->request();
        $model = $model->with('requester', 'agent')
            ->select('tickets.*')
            ->join('users', 'users.id', '=', 'tickets.user_id');

        if (!is_null($request->startDate) && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $model->where(DB::raw('DATE(tickets.updated_at)'), '>=', $startDate);
        }

        if (!is_null($request->endDate) && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $model->where(DB::raw('DATE(tickets.updated_at)'), '<=', $endDate);
        }

        if (!is_null($request->agentId) && $request->agentId != 'all') {
            $model->where('tickets.agent_id', '=', $request->agentId);
        }

        if (!is_null($request->ticketStatus) && $request->ticketStatus != 'all') {
            $request->ticketStatus == 'unassigned' ? $model->whereNull('agent_id') : $model->where('tickets.status', '=', $request->ticketStatus);
        }

        if ($request->ticketFilterStatus != '')
        {
            $request->ticketFilterStatus == 'open' ? $model->where(function ($query) {
                $query->where('tickets.status', '=', 'open')
                    ->orWhere('tickets.status', '=', 'pending');
            }) : $model->where(function ($query) {
                $query->where('tickets.status', '=', 'resolved')
                    ->orWhere('tickets.status', '=', 'closed');
            });

            if ($request->ticketFilterStatus == 'unassigned') {
                $model->whereNull('agent_id');
            }
        }

        if (!is_null($request->priority) && $request->priority != 'all') {
            $model->where('tickets.priority', '=', $request->priority);
        }

        if (!is_null($request->channelId) && $request->channelId != 'all') {

            $model->where('tickets.channel_id', '=', $request->channelId);
        }

        if (!is_null($request->typeId) && $request->typeId != 'all') {
            $model->where('tickets.type_id', '=', $request->typeId);
        }

        if (!is_null($request->tagId) && $request->tagId != 'all') {
            $model->join('ticket_tags', 'ticket_tags.ticket_id', 'tickets.id');
            $model->where('ticket_tags.tag_id', '=', $request->tagId);
        }

        if ($this->viewTicketPermission == 'owned') {
            $model->where(function ($query) {
                $query->where('tickets.user_id', '=', user()->id)
                    ->orWhere('tickets.agent_id', '=', user()->id);
            });
        }

        if ($this->viewTicketPermission == 'both') {
            $model->where(function ($query) {
                $query->where('tickets.user_id', '=', user()->id)
                    ->orWhere('tickets.added_by', '=', user()->id)
                    ->orWhere('tickets.agent_id', '=', user()->id);
            });
        }

        if ($this->viewTicketPermission == 'added') {
            $model->where('tickets.added_by', '=', user()->id);
        }

        if ($request->searchText != '') {
            $model->where(function ($query) {
                $query->where('tickets.subject', 'like', '%' . request('searchText') . '%')
                    ->orWhere('tickets.id', 'like', '%' . request('searchText') . '%')
                    ->orWhere('tickets.status', 'like', '%' . request('searchText') . '%')
                    ->orWhere('users.name', 'like', '%' . request('searchText') . '%')
                    ->orWhere('tickets.priority', 'like', '%' . request('searchText') . '%');
            });
        }

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
            ->setTableId('ticket-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(5)
            ->destroy(true)
            ->responsive(true)
            ->serverSide(true)
            /* ->stateSave(true) */
            ->processing(true)
            ->language(__('app.datatable'))
            ->parameters([
                'initComplete' => 'function () {
                    window.LaravelDataTables["ticket-table"].buttons().container()
                    .appendTo("#table-actions")
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
            __('modules.tickets.ticket') . ' #' => ['data' => 'id', 'name' => 'id', 'title' => __('modules.tickets.ticket') . ' #'],
            __('modules.tickets.ticketSubject')  => ['data' => 'subject', 'name' => 'subject', 'title' => __('modules.tickets.ticketSubject')],
            __('app.name') => ['data' => 'name', 'name' => 'user_id', 'visible' => false, 'title' => __('app.name')],
            __('modules.tickets.requesterName') => ['data' => 'user_id', 'name' => 'user_id', 'visible' => !in_array('client', user_roles()), 'exportable' => false, 'title' => __('modules.tickets.requesterName')],
            __('modules.tickets.requestedOn') => ['data' => 'updated_at', 'name' => 'updated_at', 'title' => __('modules.tickets.requestedOn')],
            __('app.others') => ['data' => 'others', 'name' => 'others', 'sortable' => false, 'title' => __('app.others')],
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
        return 'Tickets_' . date('YmdHis');
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
