    <?php
    $mamoriatMainUIIDSPLIT=\Str::before(\Str::uuid(), '-');
    ?>
    <button class="btn btn-toggle align-items-center list-group-item list-group-item-action rounded" data-bs-toggle="collapse" data-bs-target="#{{$mamoriatMainUIIDSPLIT ?? 'Drivers'}}-collapse" aria-expanded="false">
        <i class="fas fa-address-card"></i>{{__('ODLANG::MyOffice.DriverComponentName')}}
    </button>
    <div class="collapse list-group list-group-flush" id="{{$mamoriatMainUIIDSPLIT ?? 'Drivers'}}-collapse" style="">
        <button class="btn btn-toggle align-items-center list-group-item list-group-item-action rounded" data-bs-toggle="collapse" data-bs-target="#base{{$mamoriatMainUIIDSPLIT ?? 'Drivers'}}-collapse" aria-expanded="false">
            <i class="fas fa-sitemap"></i>{{__('ODLANG::MyOffice.mains')}}
        </button>
        <div class="collapse list-group list-group-flush" id="base{{$mamoriatMainUIIDSPLIT ?? 'Drivers'}}-collapse" style="">
            <a href="{{route('Drivers.office_cars.index')}}" class="list-group-item list-group-item-action"><span class="fab fa-servicestack"></span>{{__('ODLANG::MyOffice.cars.cars')}}</a>
            <a href="{{route('Drivers.office_degrees.index')}}" class="list-group-item list-group-item-action"><span class="fab fa-servicestack"></span>{{__('ODLANG::MyOffice.office_degrees.office_degrees')}}</a>
            <a href="{{route('Drivers.office_chairmen.index')}}" class="list-group-item list-group-item-action"><span class="fab fa-servicestack"></span>{{__('ODLANG::MyOffice.office_chairmen.office_chairmen')}}</a>
            <a href="{{route('Drivers.office_drivers.index')}}" class="list-group-item list-group-item-action"><span class="fab fa-servicestack"></span>{{__('ODLANG::MyOffice.office_drivers.office_drivers')}}</a>
            <a href="{{route('Drivers.office_employers.index')}}" class="list-group-item list-group-item-action"><span class="fab fa-servicestack"></span>{{__('ODLANG::MyOffice.office_employers.office_employers')}}</a>
        </div>
        <a href="{{route('Drivers.offics_chairmenmamorias.index')}}" class="list-group-item list-group-item-action"><span class="fab fa-servicestack"></span>{{__('ODLANG::MyOffice.offics_chairmenmamorias.offics_chairmenmamorias')}}</a>
        <a href="{{route('Drivers.offics_driversmamorias.index')}}" class="list-group-item list-group-item-action"><span class="fab fa-servicestack"></span>{{__('ODLANG::MyOffice.offics_driversmamorias.offics_driversmamorias')}}</a>
        <a href="{{route('Drivers.offics_employersmamorias.index')}}" class="list-group-item list-group-item-action"><span class="fab fa-servicestack"></span>{{__('ODLANG::MyOffice.offics_employersmamorias.offics_employersmamorias')}}</a>
    </div>
