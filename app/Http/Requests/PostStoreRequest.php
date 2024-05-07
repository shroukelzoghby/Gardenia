<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if(request()->isMethod('post')){
            return [
               // 'user_id'=>'required',
                'caption'=>'required|string',
                'image'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ];

        }else{
            return [
                //'user_id'=>'required',
                'caption'=>'required|string',
                'image'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ];
        }
    }
    public function messages()
    {

        if(request()->isMethod('post')){
            return [

               // 'user_id.required'=>'userid required',
                'caption.required'=>'error in caption',
                'image.required'=>'error in image '
            ];

        }else{
            return [
                //'user_id.required'=>'userid required',
                'caption.required'=>'error in caption ',
                'image.required'=>'error in image'
            ];
        }
    }


}
