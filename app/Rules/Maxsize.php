<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class Maxsize implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $total_size = array_reduce($value, function ($sum, $item)
        {
            $sum += filesize($item->path());
            return $sum;
        });

        if ($total_size > 10000 * 3200) {
            $fail('Le poids total des images doit être inférieur à 32Mo.');
        }
    }
}
