<?php
namespace Amerhendy\Drivers\App\Http\Controllers\api;
use \Amerhendy\Drivers\App\Models\offics_employersmamorias;
use Illuminate\Support\Facades\DB;
use \Amerhendy\Amer\App\Models\Governorates;
use \Amerhendy\Amer\App\Models\Cities;
use Illuminate\Validation\Rule;
use Amerhendy\Drivers\App\Rules\{checkPrintIdORArray};
use Illuminate\Support\Facades\Validator;
trait checkrequests{
    public function __construct(){
        self::$error=new \stdClass();
        self::$error->number=402;
        self::$error->page=\Str::between(\Str::after(__FILE__,__DIR__),'\\','.php');
    }
    public static function setErrorClass(){//29310021499811
        self::$error=new \stdClass();
        self::$error->number=402;
        self::$error->page=\Str::between(\Str::after(__FILE__,__DIR__),'\\','.php');
    }
    public static function printRequest(){
        $request=self::$request;
        $rules=[
            'type'=>['required',Rule::in(['short','full'])],
            'modelkey'=>['required',Rule::in(['employer','driver','chairman'])],
            'RoleId'=>['required',new checkPrintIdORArray]
        ];
        $attributes=[
            'type'=>__('ODLANG::MyOffice.type'),
            'modelkey'=>__('ODLANG::MyOffice.modelkey'),
            'RoleId'=>__('ODLANG::MyOffice.RoleId'),
        ];
        $errorMessages=[
            'required'=>__('ODLANG::MyOffice.errors.required',[':attribute']),
            'in'=>__('ODLANG::MyOffice.errors.in',[':attribute']),
            'checkPrintIdExists'=>__('ODLANG::MyOffice.errors.checkPrintIdExists'),
        ];
        $validator = Validator::make(self::$request->all(), $rules,$errorMessages,$attributes);
        if(count($validator->errors())){
            dd($validator->errors());
            self::$error->message=$validator->errors();self::$error->line=__LINE__;return \AmerHelper::responseError(self::$error,self::$error->number);
        }
        return true;
    }
}
