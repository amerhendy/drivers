<?php
namespace Amerhendy\Drivers\App\Http\Controllers\api;
use \Amerhendy\Drivers\App\Models\{offics_employersmamorias,offics_driversmamorias,offics_chairmenmamorias,office_drivers};
use Illuminate\Support\Facades\DB;
use \Amerhendy\Amer\App\Models\{Governorates,Cities};
use \Amerhendy\Drivers\App\Http\Controllers\api\{checkrequests,printTrait};
class MamoriaCollection{
    use checkrequests,printTrait;
    public static $request,$error,$MamoriaType,$mamoria_id,$mamoria,$PureData,$HtmlData;
    public static function print(){
        self::$request=Request();
        $selfcheck=self::printRequest();
        if($selfcheck !== true){
            return $selfcheck;
        }
        //get data
        if(!is_array(self::$request->RoleId)){
            self::$mamoria_id=[self::$request->RoleId];
        }else{
            self::$mamoria_id=self::$request->RoleId;
        }
        if(self::$request->modelkey == 'employer'){
            self::$MamoriaType='offics_employersmamorias';
        }elseif(self::$request->modelkey == 'driver'){
            self::$MamoriaType='offics_driversmamorias';
        }elseif(self::$request->modelkey == 'chairman'){
            self::$MamoriaType='offics_chairmenmamorias';
        }
        self::getMamoriaPure();
        self::prepMamoriasForPrint();
        //////////////////////////////////////
        $old=self::$HtmlData[0];
        //self::$HtmlData=[$old,$old,$old,$old,$old,$old,$old,$old,$old,$old,$old,$old,$old,$old,$old];
        //dd(self::$HtmlData);
        //////////////////////////////////////

        $dod=new \stdClass;
        if(self::$request->input('type') == 'full'){
            $dod->headerTitle=__('ODLANG::MyOffice.salaryfor');
            $dod->data=self::$HtmlData;
            return \AmerHelper::responsedata(self::PrinFullMamoria($dod));
        }elseif(self::$request->input('type') == 'short'){
            $dod->headerTitle=__('ODLANG::MyOffice.Itinerary');
            $dod->data=self::$HtmlData;
            return \AmerHelper::responsedata(self::PrinShortMamoria($dod));
        }

    }
    public static function getMamoriaPure(){
        if(self::$MamoriaType == 'offics_employersmamorias'){
            self::$PureData=\Amerhendy\Drivers\App\Models\offics_employersmamorias::whereIn('id',self::$mamoria_id);
        }elseif(self::$MamoriaType == 'offics_driversmamorias'){
            self::$PureData=\Amerhendy\Drivers\App\Models\offics_driversmamorias::whereIn('id',self::$mamoria_id);
        }elseif(self::$MamoriaType == 'offics_chairmenmamorias'){
            self::$PureData=\Amerhendy\Drivers\App\Models\offics_chairmenmamorias::whereIn('id',self::$mamoria_id);
        }
    }
    public static function prepMamoriasForPrint() {
        $data=[];
        foreach (self::$PureData->get() as $key => $value) {
            if(self::$MamoriaType == 'offics_employersmamorias'){
                $employerClass=$value->Employers;
            }elseif(self::$MamoriaType == 'offics_driversmamorias'){
                $employerClass=$value->Drivers;
            }elseif(self::$MamoriaType == 'offics_chairmenmamorias'){
                $employerClass=$value->Chairman;
            }
            $obj=new \stdClass;
            $obj->id=$value->id;
            $obj->reson=$value->reson;
            $obj->report=$value->report;
            $obj->startDate=self::moveDate($value->startDate);
            $obj->endDate=self::moveDate($value->endDate);
            $obj->employer_id=self::prepEmployerData($employerClass,$value->startDate);
            $obj->places=$value->arrayPlaces;
            //$obj->placesList=\Arr::map($value->arrayPlaces,function($v,$K){return $v['govs']['name'];});
            //$obj->places=$value->HtmlPlaces;
            //$obj->carornot=self::prepCarOrNotData($value->carornot);
            //dd($value);
            $obj->carornot=$value->carOrNotToHtml;
            $obj->timesMmori=$value->timesMmori;
            $obj->amountPerDay=$value->amount;
            $obj->amountForDays=$value->amount*$value->timesMmori;
            $obj->mountPerEqamea=$value->eqameamount;
            $obj->eqamadays=$value->eqamadays;
            $obj->mountPerEqameas=$value->eqameamount*$obj->eqamadays;
            $data[]=$obj;
        }
        self::$HtmlData=$data;
        return $data;
        if(self::$MamoriaType == 'offics_employersmamorias'){
            self::prepEmployersMamoriasForPrint();
        }elseif(self::$MamoriaType == 'offics_driversmamorias'){
            self::prepDriversMamoriasForPrint();
        }elseif(self::$MamoriaType == 'offics_chairmenmamorias'){
            self::prepChairmensMamoriasForPrint();
        }

    }
    public static function prepEmployersMamoriasForPrint(){
        $data=[];
        foreach (self::$PureData->get() as $key => $value) {
            $obj=new \stdClass;

            //dd($value->timesMmori);


            $obj->print=$value->print;
            $obj->exit=$value->exit;
            $obj->taswia=$value->taswia;
            $obj->eqama=$value->eqama;
            $data[]=$obj;
        }
        self::$HtmlData=$data;
    }
    public static function prepEmployerData($em,$mamoriaDate){
        $EmpData=new \stdClass;
        $EmpData->id=$em->id;
        $EmpData->name=$em->name;
        $EmpData->userid=$em->userid;
        $selected=[];
        $ifo=$em->info;
        if(\AmerHelper::isJson($ifo)){
            $ifo=json_decode($ifo,true);
        }
        foreach ($ifo as $key => $value) {
            $empstart=$value['start'];
            $empend=$value['end'];
            if(\AmerHelper::betweenTwoDates($mamoriaDate,$empstart,$empend)){
                $selected=$value;
            }
        }
        if(empty($selected)){
            $selected=\Arr::last($ifo);
        }
        if(\Str::contains($em->getTable(), 'driver')){
            $selected['job']=trans('ODLANG::MyOffice.office_drivers.singular');
        }elseif(\Str::contains($em->getTable(), 'chairmen')){
            $selected['job']=trans('ODLANG::MyOffice.employmentDegrees.jobs.'.$selected['job']);
        }
        if(\Str::contains($em->getTable(), 'chairmen')){
            $deg=null;
        }else{
            $deg=\Amerhendy\Drivers\App\Models\office_degrees::where('id',$selected['degree'])->first();
        }

        $EmpData->job=$selected['job'];
        $EmpData->degree=$deg;
        return $EmpData;
    }
    public static function moveDate($date) {
        if(is_null($date)){return null;}
        $date=\Carbon\Carbon::parse($date);
        $return=new \stdClass;
        $return->year=$date->year;
        $return->month=$date->month;
        $return->day=$date->day;
        if($date->hour > 12){$date->hour = $date->hour-12; $pm='pm';}else{$pm='am';}
        $return->hour=$date->hour;
        $return->pm=$pm;
        $return->minute=$date->minute;
        $return->second=$date->second;
        return $return;
    }
    public static function prepCarOrNotData($pl){
        dd($pl);
        if(self::$MamoriaType == 'offics_driversmamorias'){
                $obj=new \stdClass;
                $obj->Type=__('ODLANG::MyOffice.carornot.types.0');
                $obj->amount=0;
                return [$obj];
        }elseif(self::$MamoriaType == 'offics_chairmenmamorias'){
            dd("SSSSSSs");
            $employerClass=$value->Drivers;
        }
        if($pl == ''){return false;}
        if(!is_array($pl)){
            return self::prepCarOrNotData(json_decode($pl,true));
        }
        $selected=[];
        foreach ($pl as $key => $value) {
                $obj=new \stdClass;
                $obj->Type=__('ODLANG::MyOffice.carornot.types.'.$value['Type']);
                $obj->amount=$value['amount'];
                $selected[]=$obj;
        }
        return $selected;
    }
    public static function getDriversList(){
        $fst=\Arr::map(office_drivers::get('name')->toArray(), function($v,$k){return $v['name'];});
        $nd=\Arr::map(offics_employersmamorias::get('driver')->toArray(), function($v,$k){return $v['driver'];});
        $rd=\Arr::map(offics_chairmenmamorias::get('driver')->toArray(), function($v,$k){return $v['driver'];});
        $collection=collect(\Arr::collapse([$fst,$nd,$rd]));
        $collection= $collection->where(function ($item, $key) {
            return $item !== null;
        });
        $unique = $collection->unique();
        return $unique->values()->all();
    }
    public static function getresonsList(){
        //offics_employersmamorias,offics_driversmamorias,offics_chairmenmamorias
        $fst=\Arr::map(offics_employersmamorias::get('reson')->toArray(), function($v,$k){return $v['reson'];});
        $nd=\Arr::map(offics_driversmamorias::get('reson')->toArray(), function($v,$k){return $v['reson'];});
        $rd=\Arr::map(offics_chairmenmamorias::get('reson')->toArray(), function($v,$k){return $v['reson'];});
        $collection=collect(\Arr::collapse([$fst,$nd,$rd]));
        $collection= $collection->where(function ($item, $key) {
            return $item !== null;
        });
        $unique = $collection->unique();
        return $unique->values()->all();
    }
}
