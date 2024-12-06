<?php
namespace Amerhendy\Drivers\App\Http\Controllers;
use \Amerhendy\Drivers\App\Models\office_degrees as office_degrees;
use Illuminate\Support\Facades\DB;
use \Amerhendy\Amer\App\Http\Controllers\Base\AmerController;
use \Amerhendy\Amer\App\Helpers\Library\AmerPanel\AmerPanelFacade as AMER;
use \Amerhendy\Drivers\App\Http\Requests\office_degreesRequest as office_degreesRequest;

class office_degreesAmerController extends AmerController
{
    public $trlg;
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
        $this->trlg=trans('ODLANG::MyOffice.office_degrees');
        AMER::setModel(office_degrees::class);
        AMER::setRoute(config('Amer.Drivers.route_prefix') . '/office_degrees');
        AMER::setEntityNameStrings($this->trlg['singular'], $this->trlg['plural']);
        $this->Amer->setTitle($this->trlg['create'], 'create');
        $this->Amer->setHeading($this->trlg['create'], 'create');
        $this->Amer->setSubheading($this->trlg['create'], 'create');
        $this->Amer->setTitle($this->trlg['edit'], 'edit');
        $this->Amer->setHeading($this->trlg['edit'], 'edit');
        $this->Amer->setSubheading($this->trlg['edit'], 'edit');
        $this->Amer->addClause('where', 'deleted_at', '=', null);
        $this->setPermisssions('office_degrees');
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
        AMER::addColumns([
            [
                'name'=>'name',
                'type'=>'text',
                'label'=>trans('ODLANG::MyOffice.employmentDegrees.name')
            ],
        ]);
    }
    function fields(){
        $routes=$this->Amer->routelist;
        AMER::addFields([
            [
                'name'=>'name',
                'type'=>'text',
                'label'=>trans('ODLANG::MyOffice.employmentDegrees.name')
            ],
            [
                'name'=>'info',
                'type'=>'table',
                'max'=>1,
                'label'=>trans('ODLANG::MyOffice.amount.travelamount'),
                'sort'=>false,
                'changeColumn'=>false,
                'columns'=>[
                    ['first'=>['type'=>'float','label'=>trans('ODLANG::MyOffice.amount.first')]],
                    ['second'=>['type'=>'float','label'=>trans('ODLANG::MyOffice.amount.second')]],
                    ['last'=>['type'=>'float','label'=>trans('ODLANG::MyOffice.amount.last')]],
                ],
            ],
        ]);
    }
    protected function setupCreateOperation()
    {
        AMER::setValidation(office_degreesRequest::class);
        $this->fields();
    }
    protected function setupUpdateOperation()
    {
        AMER::setValidation(office_degreesRequest::class);
        $this->fields();
    }
    public function destroy($id)
    {
        $this->Amer->hasAccessOrFail('delete');
        $data=$this->Amer->model::remove_force($id);
        return $data;
    }
}
