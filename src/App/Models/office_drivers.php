<?php
namespace Amerhendy\Drivers\App\Models;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Amerhendy\Amer\App\Models\Traits\AmerTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
class office_drivers extends Model
{
    use HasFactory,SoftDeletes,AmerTrait,HasRoles,HasApiTokens,HasUuids;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'office_drivers';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'degree' => 'array'
    ];
    protected function casts(): array
    {
        return [
            'degree' => 'array',
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    function hmtlFromOld() : Attribute
    {
        return new Attribute(
            get:function (mixed $value, array $attributes){
                $arr=json_decode($attributes['info']);
                if(!is_array($arr)){return null;}
                if(!count($arr)){return null;}
                foreach($arr as $k=>$v){
                    if($v->end === ''){$selected=$k;}
                }
                $degrees=\Amerhendy\Drivers\App\Models\office_degrees::where('id',$arr[$selected]->degree)->first();
                if($degrees){return $degrees->name;}else{return null;}

            }
        );
    }
    function hmtldegree() : Attribute
    {
        return new Attribute(
            get:function (mixed $value, array $attributes){
                dd($this->hmtlFromOld);
                $arr=json_decode($attributes['info'],true);
                dd(\Arr::sort($arr,function($v,$k){
                    return $v['end'];
                }));
                $arr=\Arr::last($arr);

                if(isset($arr['degree'])){
                    $degrees=\Amerhendy\Drivers\App\Models\office_degrees::where('id',$arr['degree'])->first();
                    if($degrees){return $degrees->name;}else{return null;}
                }else{
                    return '';
                }

            }
        );
    }
}
