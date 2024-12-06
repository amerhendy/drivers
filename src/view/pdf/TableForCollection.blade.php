<?php
//dd($data->placesJ);
if(isset($data->employer_id)){
    $userid=$data->employer_id->userid;
    $Uname=$data->employer_id->name;
}elseif (isset($data->driver_id)) {
    $userid=$data->driver_id->userid;
    $Uname=$data->driver_id->name;
}
if($data->startDate !== null)
{
    $startDate=\AmerHelper::ArabicNumbersText(" ".implode('/',[$data->startDate->year,$data->startDate->month,$data->startDate->day]));
    $startTime=\AmerHelper::ArabicNumbersText(" ".implode(':',[$data->startDate->hour,$data->startDate->minute]).' '.__('ODLANG::MyOffice.time.'.$data->startDate->pm));
}else{
    $startDate="";$startTime="";

}
if($data->endDate !== null)
{
    $endDate=\AmerHelper::ArabicNumbersText(" ".implode("/",[$data->endDate->year,$data->endDate->month,$data->endDate->day]));
    $endTime=\AmerHelper::ArabicNumbersText(" ".implode(':',[$data->endDate->hour,$data->endDate->minute]).' '.__('ODLANG::MyOffice.time.'.$data->endDate->pm));
}else{
    $endDate="";$endTime="";
}
$carornot=$data->carornot;
if($data->employer_id->degree === null){
    $data->employer_id->degree=new \stdclass;
    $data->employer_id->degree->name=$data->employer_id->job;
}
$startPlace=\Arr::first($data->places);
$fullArivalPlace=\Arr::except($data->places, 0);
$fullArivalPlaceNames=\Arr::map($fullArivalPlace,function($v,$K){return $v['govs']['name'];});
$fullArivalPlaceNames=implode(' - ',$fullArivalPlaceNames);
$direction=\Arr::map($data->places,function($v,$K){return $v['govs']['name'];});
$safarRows=count($fullArivalPlace);
$adds=\Arr::map($data->places,function($v,$K){return (float) $v['adds'];});
$sumadds=array_sum($adds);
$caramount=\Arr::map($data->places,function($v,$k){return (float) $v['amount'];});
$sumacaramount=array_sum($adds);
//dd($data->places);
$travelMony="";
?>
<table class="">
    <thead>
        <tr>
            <th class="col11 fullCenter FS16">{{__('ODLANG::MyOffice.printactions.fullTitle')}}</th>
            <th class="col1">({{\AmerHelper::ArabicNumbersText(" ".$userid) ?? '.....'}})</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="col3 tdTitleText FS14">{{__('ODLANG::MyOffice.name2')}}	:</td>
            <td class="col9 FS12">{{$Uname ?? ''}}</td>
        </tr>
        <tr>
            <td class="col3 tdTitleText FS14">{{__('ODLANG::MyOffice.employmentDegrees.name')}}	:</td>
            <td class="col9 FS12">
                {{$data->employer_id->degree->name}}
            </td>
        </tr>
        <tr>
            <td class="col3 tdTitleText FS14">{{__('ODLANG::MyOffice.time.time')}}	:</td>
            <td class="col1  tdTitleText FS12">من</td>
            <td class="col3.5 FS12">{{$startDate}} - {{$startTime}}</td>
            <td class="col1  tdTitleText FS12">الى</td>
            <td class="col3.5 FS12">{{$endDate}} - {{$endTime}}</td>
        </tr>
        <tr>
            <td class="col3 tdTitleText FS14">{{__('ODLANG::MyOffice.carornot.carornot')}}	:</td>
            <td class="col9 FS12">
                <b>
                    <span lang=AR-EG>
                        {{$carornot}}
                    </span>
                </b>
            </td>
        </tr>
        <tr>
            <td class="col3 tdTitleText FS14">{{__('ODLANG::MyOffice.direction')}}	:</td>
            <td class="col9 FS12">
                <b>
                    <span lang=AR-EG>
                        {{implode(' - ',$direction ?? []) ?? '....'}}
                    </span>
                </b>
            </td>
        </tr>
        <tr>
            <td class="col3 tdTitleText FS14">{{__('ODLANG::MyOffice.reson')}}	:</td>
            <td class="col9 FS12">
                <b>
                    <span lang=AR-EG>
                        {{$data->reson ?? ''}}
                    </span>
                </b>
            </td>
        </tr>
        <tr>
            <td class="col3 fullCenter FS14">{{__('ODLANG::MyOffice.signs.whodidSign')}}</td>
            <td class="col6"></td>
            <td class="col3 fullCenter FS14">
                    {{__('ODLANG::MyOffice.signs.assign')}}<br>
                    {{__('ODLANG::MyOffice.signs.directmanger')}}<br><br>
                </td>
        </tr>
        <tr>
            <td class="col1.5 fullCenter wborder FS14">بيان</td>
            <td class="col1.5 fullCenter wborder FS14">التاريخ</td>
            <td class="col2 fullCenter wborder FS14">مكان القيام</td>
            <td class="col2 fullCenter wborder FS14">مكان الوصول</td>
            <td class="col1.5 fullCenter wborder FS14">عدد الليالى</td>
            <td class="col2 fullCenter wborder FS14">ملاحظات</td>
            <td class="col1 fullCenter wborder FS14">القيمة</td>
        </tr>
        <tr>
            <td class="col1.5 wborder FS14">بدل انتقال</td>
            <td class="col1.5 wborder fullCenter">{{$startDate}}</td>
            <td class="col2 wborder fullCenter">{{$startPlace['govs']['name']}}</td>
            <td class="col2 wborder fullCenter">{{$fullArivalPlaceNames ?? ''}}</td>

            <?php
                $eqamadays=array_sum(\Arr::map($data->places,function($v,$k){
                    return $v['days'];
                }));
            ?>
            <td class="col1.5 wborder fullCenter">{{\AmerHelper::ArabicNumbersText(" ".$eqamadays) ?? ''}}</td>
            <td class="col2"></td>
            <td class="col1 wborder fullCenter">{{--\AmerHelper::ArabicNumbersText(" ".$data->mountPerEqameas) ?? ''--}}</td>
        </tr>
        <tr>
            <td class="col1.5 FS14 wborder" rowspan="{{$safarRows === 1 ? 1 : $safarRows+1}}">بدل سفر</td>
            <td class="col1.5 wborder">{{\AmerHelper::ArabicNumbersText(" ".\Carbon\Carbon::parse($data->places[0]['startDate'])->toDateString())}}</td>
            <td class="col2 wborder">{{$data->places[0]['govs']['name']}}</td>
            <td class="col2 wborder">
                @if(isset($data->places[1])) {{$data->places[1]['govs']['name']}}@endif
            </td>
            <td class="col1.5 wborder">{{\AmerHelper::ArabicNumbersText(" ".$data->places[0]['days'])}}</td>
            <td class="col2"></td>
            <td class="col1 wborder">{{--\AmerHelper::ArabicNumbersText(" ".$data->places[0]['amount'])--}}</td>
            <?php
                //dd($fullArivalPlace[1]['startDate']);
            ?>
        </tr>
        @if($safarRows > 1)

            @for($i=1;$i<=$safarRows;$i++)
            <?php
            //dd($fullArivalPlace);
            ?>
            <tr>
                <td class="col1.5 wborder">{{\AmerHelper::ArabicNumbersText(" ".\Carbon\Carbon::parse($fullArivalPlace[$i]['startDate'])->toDateString())}}</td>
                <td class="col2 wborder">{{$fullArivalPlace[$i]['govs']['name']}}</td>
                <td class="col2 wborder">
                    @if(isset($fullArivalPlace[$i+1])) {{$fullArivalPlace[$i+1]['govs']['name']}}@endif
                </td>
                <td class="col1.5 wborder">{{\AmerHelper::ArabicNumbersText(" ".$fullArivalPlace[$i]['days'])}}</td>
                <td class="col2"></td>
                <td class="col1 wborder">{{-- \AmerHelper::ArabicNumbersText(" ".$fullArivalPlace[$i]['amount']) --}}</td>
            </tr>
            @endfor
        @endif
        <?php
        //dd($adds);
         ?>
        <tr>
            <td class="col1.5 FS12 wborder">مصاريف الانتقال</td>
            <td class="col5.5 wborder" colspan="2">
                {{--!! $sumadds > 0
                    ?
                    __('ODLANG::MyOffice.amount.adds')." ".\AmerHelper::ArabicNumbersText(" ".\Arr::join($adds,'+'))."<br>"
                    : "" !!--}}

            </td>
            <td class="col1.5 wborder"></td>
            <td class="col2"></td>
            <td class="col1 wborder">{!! $sumacaramount > 0
                ?
                \AmerHelper::ArabicNumbersText(" ".\array_sum($caramount))
                : "" !!}
                {{-- \AmerHelper::ArabicNumbersText(" ".array_sum($adds)+array_sum($caramount)) --}}</td>
        </tr>
        <tr>
            <td class="col8.5 wborder"></td>
            <td class="col2"></td>
            <td class="col1 wborder"></td>
        </tr>
        <tr>
            <td class="col10.5 wborder fullCenter FS14">{{__('ODLANG::MyOffice.longTotal')}}</td>
            <td class="col1 wborder"></td>
        </tr>
        <tr>
            <td class="col4 FS14 fullCenter">{{__('ODLANG::MyOffice.signs.mokhtas')}}</td>
            <td class="col4 FS14 fullCenter">{{__('ODLANG::MyOffice.signs.morgea')}}</td>
            <td class="col4 FS14 fullCenter">{{__('ODLANG::MyOffice.signs.assign')}}</td>
        </tr>
        <tr>
            <td class="col4 FS14 fullCenter"></td>
            <td class="col4 FS14 fullCenter"></td>
            <td class="col4 FS14 fullCenter">{{__('ODLANG::MyOffice.signs.feo')}}</td>
        </tr>
        <tr>
            <td class="col4 FS14 fullCenter">{{__('ODLANG::MyOffice.signs.aminkhzna')}}</td>
            <td class="col4 FS14 fullCenter"></td>
            <td class="col4 FS14 fullCenter"></td>
        </tr>
        <tr>
            <td class="col4"></td>
            <td class="col4"></td>
            <td class="col4 FS14 fullcenter">
                {{__('ODLANG::MyOffice.signs.recivers')}}
            </td>
        </tr>
        <tr>
            <td class="col4"></td>
            <td class="col4"></td>
            <td class="col4 FS14 fullRight">
                {{__('ODLANG::MyOffice.signs.name')}}/<br>
                {{__('ODLANG::MyOffice.signs.sign')}}/<br>
            </td>
        </tr>
        <tr>
            <td class="col1.5"></td>
            <td class="col9 wborder FS14">{{__('ODLANG::MyOffice.report')}}</td>
            <td class="col1.5"></td>
        </tr>
        <tr>
            <td class="col12  borderbottom">{!! $data->report !!}</td>
        </tr>
        <tr>
            <td class="col4 FS14 fullCenter">{{__('ODLANG::MyOffice.signs.whodidSign')}}</td>
            <td class="col4 FS14"></td>
            <td class="col4 FS14 fullCenter">{{__('ODLANG::MyOffice.signs.signauth')}}</td>
        </tr>
        <tr>
            <td class="col4 FS14"><br></td>
            <td class="col4 FS14"></td>
            <td class="col4 FS14"></td>
        </tr>
        <tr>
            <td class="col4 FS14"></td>
            <td class="col4 FS14"></td>
            <td class="col4 FS14  fullRight">{{__('ODLANG::MyOffice.signs.name')}}/<br>{{__('ODLANG::MyOffice.signs.sign')}}/</td>
        </tr>
    </tbody>
</table>
