<?php
namespace Amerhendy\Drivers\App\Models;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Amerhendy\Amer\App\Models\Traits\AmerTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use \Amerhendy\Amer\App\Models\Governorates;
use Carbon\Carbon;
class offics_chairmenmamorias extends Model
{
    use HasFactory,SoftDeletes,AmerTrait,HasRoles,HasApiTokens,HasUuids;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'offics_chairmenmamorias';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    protected $dates = ['deleted_at'];
/*
    protected $casts = [
        'info' => 'array'
    ];*/
    protected function casts(): array
    {
        return [
            'places'=>'array',
            'startend'=>'array',
        ];
    }
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    function Chairman(){
        return $this->belongsTo(\Amerhendy\Drivers\App\Models\office_chairmen::class,'chairman_id','id')->withTrashed();
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function startDate(): Attribute
    {
        return new Attribute(
            get:function (mixed $value, array $attributes){
                $startend=json_decode($attributes['startend']);
                return $startend->startDate;
            }
        );
    }
    public function endDate(): Attribute
    {
        return new Attribute(
            get:function (mixed $value, array $attributes){
                $startend=json_decode($attributes['startend']);
                return $startend->endDate;
            }
        );
    }

    public function HtmlPlaces() : Attribute {
        return new Attribute(
            get:function (mixed $value, array $attributes){
                $places=\Arr::map($this->arrayPlaces,function($v,$K){return $v['govs']['name'];});
                return implode(' - ',$places);
            });
    }
    public function arrayPlaces(): Attribute
    {
        return new Attribute(
        get:function (mixed $value, array $attributes){
            if(is_null($attributes['places'])){
                return [];
            }
                if(\Str::isJson($attributes['places'])){
                    $attributes['places']= json_decode($attributes['places'],true);
                }elseif(gettype($attributes['places']) == 'array'){
                    $attributes['places']= $attributes['places'];
                }
                $startDate=$this->startDate;
                $vo=[];
                for($i=0;$i<=count($attributes['places'])-1;$i++){
                    $newdate=Carbon::parse($startDate)->addDays($attributes['places'][$i]['days'])->toDateString();
                    $attributes['places'][$i]['startDate']=$startDate;
                    $attributes['places'][$i]['endDate']=$newdate;
                    $startDate=$newdate;
                }

                $places=$attributes['places'];
                $places=$places=\Arr::map($places,function($v,$K){
                    return Governorates::where('id',$v['govs'])->get(['id','name'])->first()->toArray();
                });
                $attributes['places']=\Arr::map($attributes['places'],function($v,$K) use($places){
                    $v['govs']=$places[$K];
                    return $v;
                });
                return $attributes['places'];
        });
    }
    public function carOrNotToHtml(): Attribute
    {
        return new Attribute(
            get:function (mixed $value, array $attributes){
                $places=json_decode($attributes['places'],true);
                $cars=\Arr::map($places,function($v,$K){return $v['Type'];});
                if(!count($cars)){return null;}
                return implode(' - ',\Arr::map($cars,function($v,$k){return trans('ODLANG::MyOffice.carornot.types.'.$v);}));
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
