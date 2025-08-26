<?php
namespace Amerhendy\Drivers\App\Http\Controllers;
use \Amerhendy\Drivers\App\Models\offics_employersmamorias as offics_employersmamorias;
use Illuminate\Support\Facades\DB;
use \Amerhendy\Amer\App\Http\Controllers\Base\AmerController;
use \Amerhendy\Amer\App\Helpers\Library\AmerPanel\AmerPanelFacade as AMER;
use \Amerhendy\Drivers\App\Http\Requests\offics_driversmamoriasRequest as offics_driversmamoriasRequest;
use \Amerhendy\Amer\App\Models\{Governorates,Cities};
use \Amerhendy\Drivers\App\Http\Controllers\api\MamoriaCollection;
class offics_employersmamoriasAmerController extends AmerController
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
        $this->trlg=trans('ODLANG::MyOffice.offics_employersmamorias');
        AMER::setModel(offics_employersmamorias::class);
        AMER::setRoute(config('Amer.Drivers.route_prefix') . '/offics_employersmamorias');
        AMER::setEntityNameStrings($this->trlg['singular'], $this->trlg['plural']);
        $this->Amer->setTitle($this->trlg['create'], 'create');
        $this->Amer->setHeading($this->trlg['create'], 'create');
        $this->Amer->setSubheading($this->trlg['create'], 'create');
        $this->Amer->setTitle($this->trlg['edit'], 'edit');
        $this->Amer->setHeading($this->trlg['edit'], 'edit');
        $this->Amer->setSubheading($this->trlg['edit'], 'edit');
        $this->Amer->addClause('where', 'deleted_at', '=', null);
        $this->setPermisssions('offics_employersmamorias');
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
        $this->setupListOperation();
    }
    protected function setupListOperation(){
        $Governorates=Cities::get(['id','name']);
        $govs=[];
        foreach ($Governorates as $k => $v) {
            $govs[$v->id]=$v->name;
        }
        AMER::addColumns([
            [
                'name'=>'employer_id',
                'type'=>'select',
                'model'=>'\Amerhendy\Drivers\App\Models\office_employers',
                'entity'=>'Employers',
                'label'=>trans('ODLANG::MyOffice.office_employers.singular'),
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
        $Governorates=$Governorates->map(function($v,$k){
            $cities=Cities::where('gov_id',$v->id)->get(['id','name']);
            $v['cities']=$cities->toArray();
            return $v;
        })->toArray();
        $govs=[];
        foreach ($Governorates as $k => $v) {
            $cititw=[];
            foreach ($v['cities'] as $l => $m) {
                $cititw[$m['id']]=['name'=>$m['name']];
            }
            $govs[$v['id']]=['name'=>$v['name'],'data'=>$cititw];
        }
        $routes=$this->Amer->routelist;

        AMER::addFields([
            [
                'tab'=>trans('ODLANG::MyOffice.name2'),
                'name'=>'employer_id',
                'type'=>'select',
                'model'=>'\Amerhendy\Drivers\App\Models\office_employers',
                'entity'=>'Employers',
                'label'=>trans('ODLANG::MyOffice.office_employers.singular'),
            ],
            [
                'tab'=>trans('ODLANG::MyOffice.name2'),
                'name'=>'driver',
                'type'=>'datalist',
                'label'=>trans('ODLANG::MyOffice.office_drivers.singular'),
                'options'=>MamoriaCollection::getDriversList(),
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
                    ['govs'=>['type'=>'selectgroup','data'=>$govs,'label'=>trans('ODLANG::MyOffice.places.govs')]],
                    ['days'=>['type'=>'number','label'=>trans('ODLANG::MyOffice.time.eqamadays')]],
                    ['Live'=>['type'=>'radio','label'=>trans('ODLANG::MyOffice.time.Live'),'data'=>trans('ODLANG::MyOffice.time.Lives'),]],
                    ['sleep'=>['type'=>'radio','label'=>trans('ODLANG::MyOffice.time.Sleep'),'data'=>trans('ODLANG::MyOffice.time.sleeps'),]],
                    ['Type'=>['type'=>'select','label'=>trans('ODLANG::MyOffice.carornot.carornot'),'data'=>trans('ODLANG::MyOffice.carornot.types')]],
                    ['amount'=>['type'=>'float','step'=>'0.01','label'=>trans('ODLANG::MyOffice.carornot.amount')],],
                    ['adds'=>['type'=>'float','step'=>'0.01','label'=>trans('ODLANG::MyOffice.amount.adds')],],
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
        if(self::CalculateDates($request) !== true){
            \Alert::add('danger','<i class="fa fa-circle-xmark"></i> '.trans('ODLANG::MyOffice.errors.CalculateDatesError'))->flash();
            return \Redirect::back();
        }
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
        //dd($this->Amer->getRequest()->request);
        $response = $this->traitStore();
        // do something after save
        return $response;
    }
    public function update()
    {
        $request=request();
        if(self::CalculateDates($request) !== true){
            \Alert::add('danger','<i class="fa fa-circle-xmark"></i> '.trans('ODLANG::MyOffice.errors.CalculateDatesError'))->flash();
            return \Redirect::back();
        }
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
        //dd(json_decode($this->Amer->getRequest()['ddad']));
        //dd($this->Amer->getRequest()->all());
        $response = $this->traitUpdate();
        // do something after save
        return $response;
    }
    public static function CalculateDates($request){
        $places=$request->places;

        $places=json_decode($places,true);
        $days=\Arr::map($places,function($v,$K){return $v['days'];});
        $startDate=\Carbon\Carbon::parse($request->input('startDate'));
        $endDate=\Carbon\Carbon::parse($request->input('endDate'));
        $newdate=\Carbon\Carbon::parse($startDate)->addDays(array_sum($days));//->toDateString();
        //def between start and end
        $stDef=$startDate->diffInDays($endDate);
        //def between start and new
        $ndDef=array_sum($days);
        if($stDef !== $ndDef){
            //return error
            return ['startDate'=>$startDate,'endDate'=>'endDate','newdate'=>$newdate];
        }
        return true;
    }
}
