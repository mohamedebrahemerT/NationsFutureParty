<?php

use App\Models\CreditNotes;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CopyCreditNotesInvoiceTableDataToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        $credit_notes_invoices = DB::table('credit_notes_invoice')->get();

        $data = [];

        foreach ($credit_notes_invoices as $credit_notes_invoice) {

            $creditNote = CreditNotes::find($credit_notes_invoice->credit_notes_id);

            $data['invoice_id'] = $credit_notes_invoice->invoice_id;
            $data['credit_notes_id'] = $credit_notes_invoice->credit_notes_id;
            $data['paid_on'] = $credit_notes_invoice->date;
            $data['amount'] = $credit_notes_invoice->credit_amount;
            $data['currency_id'] = $creditNote->currency_id;
            $data['status'] = 'complete';
            $data['gateway'] = 'credit note';
            $data['customer_id'] = $creditNote->client_id;
        }

        if(!empty($data)){
            Payment::create($data);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            //
        });
    }

}
