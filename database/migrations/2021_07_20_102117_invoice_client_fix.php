<?php

use App\Models\Invoice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InvoiceClientFix extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $invoices = Invoice::with(
            [
                'project' => function ($q) {
                    $q->withTrashed();
                    $q->select('id', 'project_name', 'client_id');
                },
                'project.client', 'client', 'estimate', 'estimate.client'
            ]
        )->whereNull('invoices.client_id')
            ->get();

        foreach($invoices as $invoice){
            if ($invoice->project && $invoice->project->client) {
                $invoice->client_id = $invoice->project->client->id;

            } else if ($invoice->estimate && $invoice->estimate->client) {
                $invoice->client_id = $invoice->estimate->client->id;

            } else {
                $invoice->client_id = null;
            }
            
            $invoice->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
