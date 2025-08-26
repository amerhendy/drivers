<?php
namespace Amerhendy\Drivers\App\Http\Controllers;
use \Amerhendy\Drivers\App\Models\office_cars as office_cars;
use Illuminate\Support\Facades\DB;
use \Amerhendy\Amer\App\Http\Controllers\Base\AmerController;
use \Amerhendy\Amer\App\Helpers\Library\AmerPanel\AmerPanelFacade as AMER;
use \Amerhendy\Drivers\App\Http\Requests\office_carsRequest as office_carsRequest;

class office_carsAmerController extends AmerController
{
    public $trlg,$cols;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\ListOperation;
    //use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\CreateOperation  {store as traitStore;}
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\CreateOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\UpdateOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\DeleteOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\ShowOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\TrashOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\CloneOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\BulkCloneOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\BulkDeleteOperation;
    use \Amerhendy\Amer\App\Http\Controllers\Base\Operations\FetchOperation;
    public function setup()
    {
        $this->trlg=trans('ODLANG::MyOffice.cars');
        $this->cols=config('Amer.Drivers.columns');
        AMER::setModel(office_cars::class);
        AMER::setRoute(config('Amer.Drivers.route_prefix') . '/office_cars');
        AMER::setEntityNameStrings($this->trlg['singular'], $this->trlg['plural']);
        $this->Amer->setTitle($this->trlg['create'], 'create');
        $this->Amer->setHeading($this->trlg['create'], 'create');
        $this->Amer->setSubheading($this->trlg['create'], 'create');
        $this->Amer->setTitle($this->trlg['edit'], 'edit');
        $this->Amer->setHeading($this->trlg['edit'], 'edit');
        $this->Amer->setSubheading($this->trlg['edit'], 'edit');
        $this->Amer->addClause('where', 'deleted_at', '=', null);
        $this->setPermisssions('office_cars');
    }
    public function setPermisssions($n){
        $this->Amer->enableDetailsRow ();
        $this->Amer->allowAccess ('details_row');
        $this->Amer->enableBulkActions();
        $accesslist=['update','list', 'show','trash','reorder','delete','create','clone','BulkDelete'];
        foreach ($accesslist as $l) {
            if(amer_user()->canper($n.'-'.$l) === false){$this->Amer->denyAccess($l);}
        }
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }
    protected function setupListOperation(){
        $cols=$this->cols;
        AMER::addColumns([
            [
                'name'=>'number',
                'type'=>'text',
                'label'=>trans('ODLANG::MyOffice.cars.number')
            ],
            [
                'name'=>'startDate',
                'type'=>'date',
                'label'=>trans('ODLANG::MyOffice.cars.start')
            ],
            [
                'name'=>'endDate',
                'type'=>'date',
                'label'=>trans('ODLANG::MyOffice.cars.end')
            ],
        ]);
    }
    function fields(){
        $cols=$this->cols;
        AMER::addFields([
            [
                'name'=>$cols['info'],
                'type'=>'table',
                'label'=>trans('ODLANG::MyOffice.cars.number'),
                'sort'=>true,
                'columns'=>[
                    ['number'=>['type'=>'text','label'=>trans('ODLANG::MyOffice.cars.number')]],
                    ['start'=>['type'=>'date','label'=>trans('ODLANG::MyOffice.cars.start')]],
                    ['end'=>['type'=>'date','label'=>trans('ODLANG::MyOffice.cars.end'),]],
                ]
            ],
        ]);
    }
    protected function setupCreateOperation()
    {
        AMER::setValidation(office_carsRequest::class);
        $this->fields();
    }
    protected function setupUpdateOperation()
    {
        AMER::setValidation(office_carsRequest::class);
        $this->fields();
    }
    public function destroy($id)
    {
        $this->Amer->hasAccessOrFail('delete');
        $data=$this->Amer->model::remove_force($id);
        return $data;
    }
}
