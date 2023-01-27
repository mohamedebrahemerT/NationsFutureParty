<?php

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;

class BaseDataTable extends DataTable
{
    protected $global;
    public $user;

    public function __construct()
    {
        $this->global = global_setting();
        $this->user = user();
    }

}
