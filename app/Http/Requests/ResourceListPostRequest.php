<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ResourceListPostRequest extends Request
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
            //
        	'cmpy' => 'required',
        	'contact' => 'required',
        	'variety' => 'required',
        	'coopManu' => 'required',
        	'province' => 'required',
        	'city' => 'required',
        	'county' => 'required',
        	'annex' => 'required',
        	'annexName' => 'required',
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    protected function formatErrors(\Illuminate\Contracts\Validation\Validator  $validator)
    {
    	return $validator->errors()->getMessages();
    }
}
