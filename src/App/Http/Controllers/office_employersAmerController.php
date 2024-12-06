<?php
namespace Amerhendy\Drivers\App\Http\Controllers;
use \Amerhendy\Drivers\App\Models\office_employers as office_employers;
use Illuminate\Support\Facades\DB;
use \Amerhendy\Amer\App\Http\Controllers\Base\AmerController;
use \Amerhendy\Amer\App\Helpers\Library\AmerPanel\AmerPanelFacade as AMER;
use \Amerhendy\Drivers\App\Http\Requests\office_employersRequest as office_employersRequest;

class office_employersAmerController extends AmerController
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
        $this->trlg=trans('ODLANG::MyOffice.office_employers');
        AMER::setModel(office_employers::class);
        AMER::setRoute(config('Amer.Drivers.route_prefix') . '/office_employers');
        AMER::setEntityNameStrings($this->trlg['singular'], $this->trlg['plural']);
        $this->Amer->setTitle($this->trlg['create'], 'create');
        $this->Amer->setHeading($this->trlg['create'], 'create');
        $this->Amer->setSubheading($this->trlg['create'], 'create');
        $this->Amer->setTitle($this->trlg['edit'], 'edit');
        $this->Amer->setHeading($this->trlg['edit'], 'edit');
        $this->Amer->setSubheading($this->trlg['edit'], 'edit');
        $this->Amer->addClause('where', 'deleted_at', '=', null);
        $this->setPermisssions('office_employers');
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
                'label'=>trans('ODLANG::MyOffice.name'),
            ],
            [
                'name'=>'userid',
                'type'=>'text',
                'label'=>trans('ODLANG::MyOffice.userid'),
            ],
            [
                'name'=>'hmtlinfo',
                'type'=>'text',
                'label'=>trans('ODLANG::MyOffice.info'),
            ],
        ]);
    }
    function fields(){
        $routes=$this->Amer->routelist;
        $degrees=\Amerhendy\Drivers\App\Models\office_degrees::get(['id','name']);
        $deg=[];
        foreach ($degrees as $k => $v) {
            $deg[$v->id]=$v->name;
        }
        AMER::addFields([
            [
                'name'=>'name',
                'type'=>'text',
                'label'=>trans('ODLANG::MyOffice.name'),
            ],
            [
                'name'=>'userid',
                'type'=>'text',
                'label'=>trans('ODLANG::MyOffice.userid'),
            ],
            [
                'name'=>'info',
                'type'=>'table',
                'max'=>10,
                'label'=>trans('ODLANG::MyOffice.info'),
                'sort'=>false,
                'changeColumn'=>false,
                'columns'=>[
                    ['job'=>['type'=>'text','label'=>trans('ODLANG::MyOffice.job')]],
                    ['degree'=>['type'=>'select','label'=>trans('ODLANG::MyOffice.employmentDegrees.name'),'data'=>$deg,]],
                    ['start'=>['type'=>'date','label'=>trans('ODLANG::MyOffice.office_degrees.start')]],
                    ['end'=>['type'=>'date','label'=>trans('ODLANG::MyOffice.office_degrees.end')]],
                ],
            ],
        ]);
    }
    protected function setupCreateOperation()
    {
        AMER::setValidation(office_employersRequest::class);
        $this->fields();
    }
    protected function setupUpdateOperation()
    {
        AMER::setValidation(office_employersRequest::class);
        $this->fields();
    }
    public function destroy($id)
    {
        $this->Amer->hasAccessOrFail('delete');
        $data=$this->Amer->model::remove_force($id);
        return $data;
    }
}
