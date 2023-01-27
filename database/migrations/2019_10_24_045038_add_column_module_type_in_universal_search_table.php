<?php

use App\Models\ClientDetails;
use App\Models\CreditNotes;
use App\Models\EmployeeDetails;
use App\Models\Estimate;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Notice;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\UniversalSearch;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnModuleTypeInUniversalSearchTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('universal_search', function (Blueprint $table) {
            $table->enum('module_type', ['ticket', 'invoice', 'notice', 'proposal', 'task', 'creditNote', 'client', 'employee', 'project', 'estimate', 'lead'])->nullable()->default(null)->after('searchable_id');
        });

        $universalSearches = UniversalSearch::all();

        if ($universalSearches->count() > 0){
            foreach ($universalSearches as $universalSearch){
                UniversalSearch::destroy($universalSearch->id);
            }
        }

        $tickets = Ticket::all();

        if ($tickets->count() > 0){
            foreach ($tickets as $ticket){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $ticket->id;
                $universalSearch->title = 'Ticket: '.$ticket->subject;
                $universalSearch->route_name = 'tickets.show';
                $universalSearch->module_type = 'ticket';
                $universalSearch->save();
            }
        }

        $proposals = Proposal::all();

        if ($proposals->count() > 0){
            foreach ($proposals as $proposal){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $proposal->id;
                $universalSearch->title = 'Proposal: '.$proposal->id;
                $universalSearch->route_name = 'proposals.edit';
                $universalSearch->module_type = 'proposal';
                $universalSearch->save();
            }
        }

        $invoices = Invoice::all();

        if ($invoices->count() > 0){
            foreach ($invoices as $invoice){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $invoice->id;
                $universalSearch->title = 'Invoice ' . $invoice->invoice_number;
                $universalSearch->route_name = 'invoices.show';
                $universalSearch->module_type = 'invoice';
                $universalSearch->save();
            }
        }

        $notices = Notice::all();

        if ($notices->count() > 0){
            foreach ($notices as $notice){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $notice->id;
                $universalSearch->title = 'Notice: '.$notice->heading;
                $universalSearch->route_name = 'notices.edit';
                $universalSearch->module_type = 'notice';
                $universalSearch->save();
            }
        }

        $tasks = Task::all();

        if ($tasks->count() > 0){
            foreach ($tasks as $task){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $task->id;
                $universalSearch->title = 'Task: '.$task->heading;
                $universalSearch->route_name = 'tasks.edit';
                $universalSearch->module_type = 'task';
                $universalSearch->save();
            }
        }

        $creditNotes = CreditNotes::all();

        if ($creditNotes->count() > 0){
            foreach ($creditNotes as $creditNote){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $creditNote->id;
                $universalSearch->title = 'Credit Note: '.$creditNote->cn_number;
                $universalSearch->route_name = 'creditnotes.show';
                $universalSearch->module_type = 'creditNote';
                $universalSearch->save();
            }
        }

        $projects = Project::all();

        if ($projects->count() > 0){
            foreach ($projects as $project){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $project->id;
                $universalSearch->title = 'Project: '.$project->project_name;
                $universalSearch->route_name = 'projects.show';
                $universalSearch->module_type = 'project';
                $universalSearch->save();
            }
        }

        $estimates = Estimate::all();

        if ($estimates->count() > 0){
            foreach ($estimates as $estimate){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $estimate->id;
                $universalSearch->title = 'Estimate #'.$estimate->id;
                $universalSearch->route_name = 'estimates.edit';
                $universalSearch->module_type = 'estimate';
                $universalSearch->save();
            }
        }

        $leads = Lead::all();

        if ($leads->count() > 0){
            foreach ($leads as $lead){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $lead->id;
                $universalSearch->title = $lead->client_name;
                $universalSearch->route_name = 'leads.show';
                $universalSearch->module_type = 'lead';
                $universalSearch->save();

                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $lead->id;
                $universalSearch->title = $lead->client_email;
                $universalSearch->route_name = 'leads.show';
                $universalSearch->module_type = 'lead';
                $universalSearch->save();

                if ($lead->company_name){
                    $universalSearch = new UniversalSearch();
                    $universalSearch->searchable_id = $lead->id;
                    $universalSearch->title = $lead->company_name;
                    $universalSearch->route_name = 'leads.show';
                    $universalSearch->module_type = 'lead';
                    $universalSearch->save();
                }
            }
        }


        $employees = EmployeeDetails::with('user')->get();

        if ($employees->count() > 0){
            foreach ($employees as $employee){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $employee->user_id;
                $universalSearch->title = 'Employee '.$employee->user->name;
                $universalSearch->route_name = 'employees.show';
                $universalSearch->module_type = 'employee';
                $universalSearch->save();

                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $employee->user_id;
                $universalSearch->title = 'Employee '.$employee->user->email;
                $universalSearch->route_name = 'employees.show';
                $universalSearch->module_type = 'employee';
                $universalSearch->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('universal_search', function (Blueprint $table) {
            $table->dropColumn('module_type');
        });
    }

}
