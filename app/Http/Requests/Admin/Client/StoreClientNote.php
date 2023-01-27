<?php

namespace App\Http\Requests\Admin\Client;

use App\Http\Requests\CoreRequest;

class StoreClientNote extends CoreRequest
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
        $rules = [
            'title' => 'required',
            'details' => 'required',
        ];

        if ($this->notes_type == 'private' && is_null($this->user_id) && is_null($this->is_client_show)) {
            $rules['user_id'] = 'required';
        }
        
        return $rules;
    }

}
