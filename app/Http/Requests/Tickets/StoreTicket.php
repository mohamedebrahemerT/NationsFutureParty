<?php

namespace App\Http\Requests\Tickets;

use App\Http\Requests\CoreRequest;

class StoreTicket extends CoreRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subject' => 'required',
            'description' => [
                'required',
                function ($attribute, $value, $fail) {
                    $commnet = trim(str_replace('<p><br></p>', '', $value));

                    if ($commnet == '') {
                        $fail(__('validation.required'));
                    }
                }
            ],
            'priority' => 'required',
            'user_id' => 'required_if:requester_type,employee',
            'client_id' => 'required_if:requester_type,client',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required_if' => __('modules.tickets.requesterName') . ' ' . __('app.required'),
            'client_id.required_if' => __('modules.tickets.requesterName') . ' ' . __('app.required'),
        ];
    }

}
