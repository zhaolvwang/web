<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserPostRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	//$uid = $this->route();
    	//dd($uid);
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
        	'mobile' => 'required',
        	'uname' => 'required',
        	'cmpy' => 'required',
        	'password' => 'required',
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    protected function formatErrors(\Illuminate\Contracts\Validation\Validator  $validator)
    {
    	return $validator->errors()->all();
    }
}
