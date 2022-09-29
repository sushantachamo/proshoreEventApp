<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Event extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['title', 'description', 'start_date', 'end_date'];

    /**
     * Get the validation rules that apply to the request.
     *
     * @param Request $request
     * @return array
     */
    public function rules()
    {
        
        $rules = [
            'title'                         => ['required', 'max:100'],
            'description'                   => ['required'],
            'start_date'                    => ['required','date'],
            'end_date'                      => ['required', 'date'],
        ];

        return $rules;
    }

}
