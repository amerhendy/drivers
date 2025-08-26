<?php
//dd($data);
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
$barcodeobj=new \TCPDFBarcode($data->id, 'C39');
$base64 = 'data:image/png;base64,' . base64_encode($barcodeobj->getBarcodePngData());
$cop=[config('Amer.Amer.co_name'),config('Amer.Amer.hc_name'),config('Amer.Amer.min_name')];
$places=\Arr::map($data->places, function($v,$k){
        return ($v['govs']['name']);
    });
    $places= count($places)>0 ? "<br>".implode(' - ',$places) : "";
?>
<div class="maindiv">
<table dir=rtl border=1 cellspacing=0 cellpadding=0 style="text-align: center;">
    <thead>
        <tr>
            <th class="borderbottom col5">
                <b>
                    <span lang="AR-EG" style="font-size: 14px;text-align:center;">
                        {{config('Amer.Amer.co_name')}}
                    </span>
                    <br>
                    <span lang="AR-EG" style="font-size: 12px;text-align:center;">
                        {{config('Amer.Amer.hc_name')}}
                    </span>
                    <br>
                    <span lang="AR-EG" style="font-size: 12px;text-align:center;">
                    {{config('Amer.Amer.min_name')}}
                    </span>
                </b>
            </th>
            <th colspan="1" class="col2 imgTd borderbottom" style="padding:40mm">

                <img class="logoImg" src="{{config('Amer.Amer.public_path')}}/{{config('Amer.Amer.co_logoGif')}}" align="center">
            </th>
            <th valign=top class="borderbottom col5 ednm">
                <b>
                    <span lang="AR-EG" style="font-size: 10px;text-align:center;">
                        {{config('Amer.Amer.co_name_english')}}
                    </span>
                    <br>
                    <span lang="AR-EG" style="font-size: 10px;text-align:center;">
                        {{config('Amer.Amer.hc_name_english')}}
                    </span>
                    <br>
                    <span lang="AR-EG" style="font-size: 10px;text-align:center;">
                    {{config('Amer.Amer.min_name_english')}}
                </span>
                </b>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th valign=top class="col4 fullRight">
                <b><span lang="EN-Uk">{{__('ODLANG::MyOffice.edaraname')}}</span></b>
            </th>
            <th valign=top class="col6  FS16"><b><span lang="EN-Uk">{{__('ODLANG::MyOffice.Itinerary')}}</span></b></th>
                <th valign="top" style="text-align: left;" class="col2 fullLeft">{{$userid ?? '......'}}</th>
        </tr>
        <tr>
            <th class="col2 fullRight  FS14">
                <b>
                    <span lang=AR-EG>
                        {{__('ODLANG::MyOffice.theName')}}
                    </span>
                </b>
            </th>
            <td class="col10 fullRight">
                <b>
                <span lang=AR-EG>
                {{$Uname ?? ''}}
                    </span>
                </b>
            </td>
        </tr>
        <tr>
                <th class="col2 fullRight  FS14">
                <b>
                <span lang=AR-EG>
                    {{__('ODLANG::MyOffice.edara')}}
                    </span>
                </b>
            </th>
            <td class="col10 fullRight">
                <b>
                <span lang=AR-EG>
                    {{__('ODLANG::MyOffice.edaraname')}}
                    </span>
                </b>
            </td>
        </tr>
        <tr>
            <th class="col2 fullRight FS14">
                <b>
                    <span lang=AR-EG>
                        {{__('ODLANG::MyOffice.modelkey')}}
                    </span>
                </b>
            </th>
            <td class="col10 fullRight">
                <b>
                    <span lang=AR-EG>
                        {{$data->reson ?? ''}} <br> {!!$places!!}.
                    </span>
                </b>
            </td>
        </tr>
        <tr>
            <th class="col2 fullRight FS14">
                <b>
                    <span lang=AR-EG>
                        {{__('ODLANG::MyOffice.time.move')}}
                    </span>
                </b>
            </th>
            <td class="col5">
                <b>
                    <span lang=AR-EG>
                        {{$startTime ?? ''}}
                    </span>
                </b>
            </td>
            <th class="col2 fullRight FS14">
                <b>
                    <span lang=AR-EG>
                        {{__('ODLANG::MyOffice.date.onDate')}}
                    </span>
                </b>
            </th>
            <td class="col3 fullRight">
                <b>
                    <span lang=AR-EG>
                        {{$startDate ?? ''}}
                    </span>
                </b>
            </td>
        </tr>
        <tr>
            <th class="col2 fullRight FS14">
                <b>
                    <span lang=AR-EG>
                        {{__('ODLANG::MyOffice.time.back')}}
                    </span>
                </b>
            </th>
            <td class="col5">
                <b>
                    <span lang=AR-EG>
                        {{$endTime ?? ''}}
                    </span>
                </b>
            </td>
            <th class="col2 fullRight FS14">
                <b>
                    <span lang=AR-EG>
                        {{__('ODLANG::MyOffice.date.onDate')}}
                    </span>
                </b>
            </th>
            <td class="col3 fullRight">
                <b>
                    <span lang=AR-EG>
                        {{$endDate ?? ''}}
                    </span>
                </b>
            </td>
        </tr>
        <tr>
            <th class="col2 fullRight FS14">
                <b>
                <span lang=AR-EG>
                {{__('ODLANG::MyOffice.signs.directmanger')}}
                </span>
                </b>
            </th>
            <th class="col7"></th>
            <th class="col3 sign fullCenter FS14">
                <b>
                    <span lang=AR-EG>
                        {{__('ODLANG::MyOffice.signs.assign')}}
                            <br>
                            {{__('ODLANG::MyOffice.signs.administrator')}}
                    </span>
                </b>
            </th>
        </tr>
        <tr>
            <td class="tdTitleText" style="height: 2cm;"><br></td>
        </tr>
    </tbody>

    <tfoot>
        <tr>
            <th class="col4 border_left border_top">{!! \AmerHelper::ArabicNumbersText(" ".implode('<br>',config('Amer.Amer.short_address'))) !!}</th>
            <?php
            //dd(data_get(config('Amer.Amer.socialmedia.phone'),'*.link'));
            ?>
            <th class="col4 border_left border_top">ت:{!! \AmerHelper::ArabicNumbersText(" ".implode('<br>',data_get(config('Amer.Amer.socialmedia.phone'),'*.link'))) !!}</th>
            <th class="col4 border_top">ف:{!! \AmerHelper::ArabicNumbersText(" ".implode('<br>',data_get(config('Amer.Amer.socialmedia.fax'),'*.link'))) !!}</th>
        </tr>
    </tfoot>
</table>
</div>
