<?php
namespace Amerhendy\Drivers\App\Http\Controllers;
use \Amerhendy\Drivers\App\Models\offics_driversmamorias as offics_driversmamorias;
use Illuminate\Support\Facades\DB;
use \Amerhendy\Amer\App\Http\Controllers\Base\AmerController;
use \Amerhendy\Amer\App\Helpers\Library\AmerPanel\AmerPanelFacade as AMER;
use \Amerhendy\Drivers\App\Http\Requests\offics_driversmamoriasRequest as offics_driversmamoriasRequest;
use \Amerhendy\Amer\App\Models\Governorates;
use \Amerhendy\Amer\App\Models\Cities;
use \Amerhendy\Drivers\App\Http\Controllers\api\MamoriaCollection;
class offics_driversmamoriasAmerController extends AmerController
{
    public $trlg;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\ListOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\CreateOperation  {store as traitStore;}
    //use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\CreateOperation;
    //use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\UpdateOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\UpdateOperation   {update as traitUpdate;}
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\DeleteOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\ShowOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\TrashOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\CloneOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\BulkCloneOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\BulkDeleteOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\FetchOperation;
    public function setup()
    {
        $this->trlg=trans('ODLANG::MyOffice.offics_driversmamorias');
        AMER::setModel(offics_driversmamorias::class);
        AMER::setRoute(config('Amer.Drivers.route_prefix') . '/offics_driversmamorias');
        AMER::setEntityNameStrings($this->trlg['singular'], $this->trlg['plural']);
        $this->Amer->setTitle($this->trlg['create'], 'create');
        $this->Amer->setHeading($this->trlg['create'], 'create');
        $this->Amer->setSubheading($this->trlg['create'], 'create');
        $this->Amer->setTitle($this->trlg['edit'], 'edit');
        $this->Amer->setHeading($this->trlg['edit'], 'edit');
        $this->Amer->setSubheading($this->trlg['edit'], 'edit');
        $this->Amer->addClause('where', 'deleted_at', '=', null);
        $this->setPermisssions('offics_driversmamorias');
    }
    public function setPermisssions($n){
        $this->Amer->enableDetailsRow ();
        $this->Amer->allowAccess ('details_row');
        if(amer_user()->canper($n.'-print') === true){
            $this->Amer->enableBulkActions();
            $this->Amer->addButton('bottom', 'bottommamoria','view', 'Drivers::admin.btnPrint-bottommamoria', 'top');
            $this->Amer->addButton('line', 'line','view', 'Drivers::admin.btnPrint-mamoria', 'beginning');
        }
        $accesslist=['update','list', 'show','trash','bulkClone','reorder','delete','create','clone','BulkDelete','print'];
        foreach ($accesslist as $l) {
            if(amer_user()->canper($n.'-'.$l) === false){$this->Amer->denyAccess($l);}
        }
    }
    protected function setupShowOperation()
    {
        AMER::addClause('active'); // apply a local scope
        $this->setupListOperation();
    }
    protected function setupListOperation(){
        $Governorates=Governorates::get(['id','name']);
        $govs=[];
        foreach ($Governorates as $k => $v) {
            $govs[$v->id]=$v->name;
        }
        AMER::addColumns([
            [
                'name'=>'driver_id',
                'type'=>'select',
                'model'=>'\Amerhendy\Drivers\App\Models\office_drivers',
                'entity'=>'Drivers',
                'label'=>trans('ODLANG::MyOffice.office_drivers.singular'),
            ],
            [
                'name'=>'HtmlPlaces',
                'type'=>'text',
                'label'=>trans('ODLANG::MyOffice.places.places'),
                'get'=>[
                    'govs'=>['data'=>$govs,'KeyTranslate'=>'']
                ]
            ],
            [
                'name'=>'startDate',
                'type'=>'datetime',
                'label'=>$this->trlg['startDate'],
                'format'=>'Y:MMMM:D - h:m',
            ],
            [
                'name'=>'endDate',
                'type'=>'datetime',
                'label'=>$this->trlg['endDate'],
                'format'=>'Y:MMMM:D - h:m',
            ],
            [
                'name'=>'timesMmori',
                'type'=>'text',
                'label'=>$this->trlg['times'],
            ],
            [
                'name'=>'print',
                'type'=>'check',
                'label'=>$this->trlg['print'],
            ],
            [
                'name'=>'exit',
                'type'=>'check',
                'label'=>$this->trlg['exit'],
            ],
            [
                'name'=>'taswia',
                'type'=>'check',
                'label'=>$this->trlg['taswia'],
            ],
            [
                'name'=>'eqama',
                'type'=>'check',
                'label'=>$this->trlg['eqama'],
            ],
        ]);
    }
    function fields(){
        $Governorates=Governorates::get(['id','name']);
        $govs=[];
        foreach ($Governorates as $k => $v) {
            $govs[$v->id]=$v->name;
        }
        $routes=$this->Amer->routelist;
        AMER::addFields([
            [
                'tab'=>trans('ODLANG::MyOffice.name2'),
                'name'=>'driver_id',
                'type'=>'select',
                'model'=>'\Amerhendy\Drivers\App\Models\office_drivers',
                'entity'=>'Drivers',
                'label'=>trans('ODLANG::MyOffice.office_drivers.singular'),
            ],
            [
                'tab'=>trans('ODLANG::MyOffice.name2'),
                'name'=>'car_id',
                'type'=>'select',
                'model'=>'\Amerhendy\Drivers\App\Models\office_cars',
                'entity'=>'Cars',
                'label'=>trans('ODLANG::MyOffice.cars.singular'),
            ],
            [
                'tab'=>trans('ODLANG::MyOffice.direction').'-'.trans('ODLANG::MyOffice.time.time'),
                'name'=>'places',
                'type'=>'table',
                'max'=>10,
                'label'=>trans('ODLANG::MyOffice.places.places'),
                'sort'=>false,
                'changeColumn'=>false,
                'columns'=>[
                    ['govs'=>['type'=>'select','data'=>$govs,'label'=>trans('ODLANG::MyOffice.places.govs')]],
                    ['days'=>['type'=>'number','label'=>trans('ODLANG::MyOffice.time.eqamadays')]],
                    ['sleep'=>['type'=>'checkbox','label'=>trans('ODLANG::MyOffice.time.Sleep'),'data'=>trans('ODLANG::MyOffice.time.sleeps'),]],
                    ['Type'=>['type'=>'select','label'=>trans('ODLANG::MyOffice.carornot.carornot'),'data'=>trans('ODLANG::MyOffice.carornot.types')]],
                    ['amount'=>['type'=>'number','label'=>trans('ODLANG::MyOffice.carornot.amount')],],
                    ['adds'=>['type'=>'number','label'=>trans('ODLANG::MyOffice.amount.adds')],],
                ],
            ],
            [
                'tab'=>trans('ODLANG::MyOffice.direction').'-'.trans('ODLANG::MyOffice.time.time'),
                'name'=>'startDate',
                'type'=>'datetime_picker',
                'label'=>$this->trlg['startDate']
            ],
            [
                'tab'=>trans('ODLANG::MyOffice.direction').'-'.trans('ODLANG::MyOffice.time.time'),
                'name'=>'endDate',
                'type'=>'datetime_picker',
                'label'=>$this->trlg['endDate']
            ],
            [
                'tab'=>trans('ODLANG::MyOffice.direction').'-'.trans('ODLANG::MyOffice.time.time'),
                'name'=>'ddad',
                'type'=>'table',
                'label'=>$this->trlg['ddad']['ddad'],
                'sort'=>false,
                'changeColumn'=>false,
                'columns'=>[
                    ['start'=>['type'=>'number','label'=>$this->trlg['ddad']['start']]],
                    ['end'=>['type'=>'number','label'=>$this->trlg['ddad']['end']]],
                ],
            ],
            [
                'tab'=>trans('ODLANG::MyOffice.amount.amount'),
                'name'=>'amount',
                'type'=>'mony',
                'label'=>trans('ODLANG::MyOffice.amount.amount'),
                'currency'=>config('Amer.Amer.Currency'),
            ],
            [
                'tab'=>trans('ODLANG::MyOffice.amount.amount'),
                'name'=>'eqameamount',
                'type'=>'mony',
                'currency'=>config('Amer.Amer.Currency'),
                'label'=>trans('ODLANG::MyOffice.amount.eqameamount'),
                'step'=>'any'
            ],
            [
                'tab'=>trans('ODLANG::MyOffice.reson-report'),
                'name'=>'reson',
                'type'=>'datalist',
                'label'=>trans('ODLANG::MyOffice.reson'),
                'options'=>MamoriaCollection::getresonsList(),
                //dd(MamoriaCollection::getresonsList());
            ],
            [
                'tab'=>trans('ODLANG::MyOffice.reson-report'),
                'name'=>'report',
                'type'=>'wysiwyg',
                'extra_plugins'=>'full',
                'label'=>trans('ODLANG::MyOffice.report'),
            ],
            [
                'tab'=>$this->trlg['print'].'-'.$this->trlg['exit'].'-'.$this->trlg['taswia'],
                'name'=>'print',
                'type'=>'checkbox',
                'label'=>$this->trlg['print'],
            ],
            [
                'tab'=>$this->trlg['print'].'-'.$this->trlg['exit'].'-'.$this->trlg['taswia'],
                'name'=>'exit',
                'type'=>'checkbox',
                'label'=>$this->trlg['exit'],
            ],
            [
                'tab'=>$this->trlg['print'].'-'.$this->trlg['exit'].'-'.$this->trlg['taswia'],
                'name'=>'taswia',
                'type'=>'checkbox',
                'label'=>$this->trlg['taswia'],
            ],
            [
                'tab'=>$this->trlg['print'].'-'.$this->trlg['exit'].'-'.$this->trlg['taswia'],
                'name'=>'eqama',
                'type'=>'checkbox',
                'label'=>$this->trlg['eqama'],
            ],
        ]);
    }
    protected function setupCreateOperation()
    {
        AMER::setValidation(offics_driversmamoriasRequest::class);
        $this->fields();
    }
    protected function setupUpdateOperation()
    {
        AMER::setValidation(offics_driversmamoriasRequest::class);
        $this->fields();
    }
    public function destroy($id)
    {
        $this->Amer->hasAccessOrFail('delete');
        $data=$this->Amer->model::remove_force($id);
        return $data;
    }
    public function store()
    {
        $request=Request();
        $this->Amer->addField(['type' => 'hidden', 'name' => 'startend']);
        $this->Amer->getRequest()->request->add(['startend'=> json_encode(['startDate'=>$request->input('startDate'),'endDate'=>$request->input('endDate')])]);
        $this->Amer->getRequest()->request->remove('startDate');
        $this->Amer->getRequest()->request->remove('endDate');
        $amount=\Str::replace(config('Amer.Amer.Currency'), '', $request->input('amount'));
        $amount=preg_replace('/\xc2\xa0/', '', $amount);
        $this->Amer->getRequest()->request->remove('amount');
        $this->Amer->getRequest()->request->add(['amount'=>$amount]);
        $eqameamount=\Str::replace(config('Amer.Amer.Currency'), '', $request->input('eqameamount'));
        $eqameamount=preg_replace('/\xc2\xa0/', '', $eqameamount);
        $this->Amer->getRequest()->request->remove('eqameamount');
        $this->Amer->getRequest()->request->add(['eqameamount'=>$eqameamount]);
        $response = $this->traitStore();
        // do something after save
        return $response;
    }
    public function update()
    {
        $request=request();
        $this->Amer->addField(['type' => 'hidden', 'name' => 'startend']);
        $this->Amer->getRequest()->request->add(['startend'=> json_encode(['startDate'=>$request->input('startDate'),'endDate'=>$request->input('endDate')])]);
        $this->Amer->getRequest()->request->remove('startDate');
        $this->Amer->getRequest()->request->remove('endDate');
        $amount=\Str::replace(config('Amer.Amer.Currency'), '', $request->input('amount'));
        $amount=preg_replace('/\xc2\xa0/', '', $amount);
        $this->Amer->getRequest()->request->remove('amount');
        $this->Amer->getRequest()->request->add(['amount'=>$amount]);
        $eqameamount=\Str::replace(config('Amer.Amer.Currency'), '', $request->input('eqameamount'));
        $eqameamount=preg_replace('/\xc2\xa0/', '', $eqameamount);
        $this->Amer->getRequest()->request->remove('eqameamount');
        $this->Amer->getRequest()->request->add(['eqameamount'=>$eqameamount]);
        //dd($request->all());
        //dd(json_decode($this->Amer->getRequest()['ddad']));
        //dd($this->Amer->getRequest()->all());
        $response = $this->traitUpdate();
        // do something after save
        return $response;
    }
}
