<?php

namespace App\Http\Requests\Channel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChannelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
//        dd($this->channel);
        return  $this->channel->user_id === auth()->user()->id;
//        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'image' => 'image',
            'description' => 'required| max:100'
        ];
    }
}
