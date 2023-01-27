<?php

use App\Models\LeadCustomForm;
use Illuminate\Database\Migrations\Migration;

class AddSeveralFieldsToLeadCustomFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        $fields = ['City', 'State', 'Country', 'Postal Code'];
        $fieldsName = ['city', 'state', 'country', 'postal_code'];

        foreach ($fields as $key => $value) {
            LeadCustomForm::create([
                'field_display_name' => $value,
                'field_name' => $fieldsName[$key],
                'field_order' => $key + 1
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $fields = ['City', 'State', 'Country', 'Postal Code'];

        foreach ($fields as $value) {
            LeadCustomForm::where('field_display_name', $value)->delete();
        }
    }

}
