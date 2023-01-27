<?php

namespace App\Http\Requests\Lead;

use App\Http\Requests\CoreRequest;
use App\Models\Setting;

class StorePublicLead extends CoreRequest
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
        $setting = Setting::first();

        $rules = [
            'name' => 'required',
            'email' => 'required|email:rfc|unique:leads,client_email|unique:users,email',
        ];

        if($setting->google_recaptcha_status == 'active' && $setting->ticket_form_google_captcha == 1 && ($setting->google_recaptcha_v2_status == 'active')){
            $rules['g-recaptcha-response'] = 'required';
        }

        return $rules;
    }

}
