<?php
namespace Amerhendy\Drivers\App\Rules;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class checkPrintIdORArray implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $request=request();
        if(!$request->has('modelkey')){
            $fail(__('ODLANG::MyOffice.errors.required',[':attribute'=>__('ODLANG::MyOffice.modelkey')]));
            return;
        }
        $value=self::checkids($value);
        if($value == false){
            $fail(__('ODLANG::MyOffice.errors.checkPrintIdExists'));
            return;
        }
        $modelkey=$request->input('modelkey');
        if($modelkey == 'chairman'){
            $check=\Amerhendy\Drivers\App\Models\offics_chairmenmamorias::find($value)->count();
                    if($check !== count($value)){
                        $fail(__('ODLANG::MyOffice.errors.checkPrintIdExists'));
                        return;
                    }
        }
        elseif($modelkey == 'driver'){
            $check=\Amerhendy\Drivers\App\Models\offics_driversmamorias::find($value)->count();
                if($check !== count($value)){
                        $fail(__('ODLANG::MyOffice.errors.checkPrintIdExists'));
                        return;
                    }
        }
        elseif($modelkey == 'employer'){
            //checkemployer
            $check=\Amerhendy\Drivers\App\Models\offics_employersmamorias::find($value)->count();
                if($check !== count($value)){
                        $fail(__('ODLANG::MyOffice.errors.checkPrintIdExists'));
                        return;
                    }
        }
    }
    private function checkids($ids){
        if(!is_array($ids)){
            $ids=[$ids];
        }
        foreach ($ids as $key => $id) {
            if(!\Str::isUuid($id)){
                return false;
            }
        }
        return $ids;
    }
}
