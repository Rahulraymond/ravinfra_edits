<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Progress;
class IsDescAvailable implements Rule
{
    private $year;
    private $week;
    private $month;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($year, $week, $month)
    {
        $this->year = $year;
        $this->week = $week;
        $this->month = $month;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($value == ''){
            return 1;
        }
        $isExists = Progress::where('year',$this->year)->where('month',$this->month)->where('week',$this->week)->where('desc','!=',null)->exists();
        if($isExists){
            return 0;
        }
        return 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You can not add description to this progress.This progress have already description';
    }
}
