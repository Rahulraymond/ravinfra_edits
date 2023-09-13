<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgressUpdate extends FormRequest
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
            'week' => 'required',

            'year' => 'required',
            
            'month' => 'required',

            'caption' => 'required|min:10'
        ];

        $rules['media'] = 'max:10';

        $rules['media.*'] = 'mimes:jpeg,jpg,png,gif,mp4,ogx,oga,ogv,ogg,webm|max:5000';
        
        return $rules;
    }

    public function messages()
    {
        // use custom message instead of using defauilt message in laravel.
        return [
            'media.max' => 'You can not upload more than 10 images at a time.',
            'media.*' => 'This media not allowed here',
        ];
    }
}
