<?php
$entryTable=$entry->getTable();
//dd($entryTable);
if(\Str::contains($entryTable, 'chairmen')){
    $modelKey='chairman';
}elseif(\Str::contains($entryTable, 'employer')){
    $modelKey='employer';
}elseif(\Str::contains($entryTable, 'driver')){
    $modelKey="driver";
}
?>
<div class="dropdown" style="display: inline;">
    <button class="btn btn-sm btn-success dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-print" aria-hidden="true"></i>
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
        <li>
            <a
                href="javascript:void(0)"
                onclick="ShowprintMamoria(this,'{{url('api/v1/DriversApi/print')}}')"
                data-entry="{{$entry->getKey()}}"
                data-mdb-ripple-duration="0s"
                class="btn btn-sm btn-success dropdown-item"
                data-button-type="ShowprintMamoria"
                data-model-key="{{$modelKey}}"
                data-title="0"
                data-type="full"
            >
                <i class="fa fa-print" aria-hidden="true"></i>
                <span>{{trans('ODLANG::MyOffice.printactions.full')}}</span>
            </a>
        </li>
        <li>
            <a
                href="javascript:void(0)"
                onclick="ShowprintMamoria(this,'{{url('api/v1/DriversApi/print')}}')"
                data-entry="{{$entry->getKey()}}"
                data-mdb-ripple-duration="0s"
                class="btn btn-sm btn-success dropdown-item"
                data-button-type="ShowprintMamoria"
                data-model-key="{{$modelKey}}"
                data-title="0"
                data-type="short"
            >
                <i class="fa fa-print" aria-hidden="true"></i>
                <span>{{trans('ODLANG::MyOffice.printactions.short')}}</span>
            </a>
        </li>

    </ul>
  </div>
@push('after_scripts') @if (request()->ajax()) @endpush @endif
@loadOnce('ShowprintMamoria')
@loadScriptOnce('js/Drivers/printMamoria.js')
@endLoadOnce
@if (!request()->ajax()) @endpush @endif
