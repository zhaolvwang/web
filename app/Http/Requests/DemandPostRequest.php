<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DemandPostRequest extends Request
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
        	'pname' => 'required',
        	'variety' => 'required',
        	'alloyNum' => 'required',
        	'standard' => 'required',
        	'intentPrc' => 'required',
        	'qty' => 'required',
        	'province' => 'required',
        	'city' => 'required',
        	'county' => 'required',
        	'payDays' => 'required',
        	'deliveryDt' => 'required',
        	'unitWeight' => 'required',
        	'content' => 'required',
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    protected function formatErrors(\Illuminate\Contracts\Validation\Validator  $validator)
    {
    	/*
    	 * 修改error中的attributes
    	 */
    	$invalidArray = $validator->invalid();
    	foreach ($invalidArray as $key => &$v) {
    		if ($key == 'province' || $key == 'city' || $key == 'county') {
    			$v = trans('panel::fields.receivePlace').trans('panel::fields.'.$key);
    		}
    		else {
    			if ($key == 'content') {
    				$v = '需求内容';
    			}
    			else {
    				$v = trans('panel::fields.'.$key);
    			}
    		}
    	}
    	$validator->setAttributeNames($invalidArray);
    	$validator->passes();
    	return $validator->errors()->all();
    }
}
