<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
        $rules = [];

        if (isset($this->customer['id']))
        {
            $rules['customer.email'] = 'required|unique:customers,email,'.$this->customer['id'];
        }
        else
        {
            $rules['customer.first_name'] = 'required';
            $rules['customer.last_name'] = 'required';
            $rules['customer.location_id'] = 'required';
            $rules['customer.email'] = 'required|unique:customers,email';
            $rules['credit.source'] = 'required';
            $rules['credit.purchase_datetime'] = 'required';
            $rules['credit.duration'] = 'required';
            $rules['credit.purchase_price'] = 'required';
        }

        return isset($this->action) && $this->action=='editNote' ? [] : $rules;
    }

    public function response(array $errors)
    {
        return response()->json($errors);
    }
}
