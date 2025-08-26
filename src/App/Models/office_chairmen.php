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
class office_chairmen extends Model
{
    use HasFactory,SoftDeletes,AmerTrait,HasRoles,HasApiTokens,HasUuids;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'office_chairmen';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    protected $dates = ['deleted_at'];

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

    public function startDate(): Attribute
    {
        return new Attribute(
            get:function (mixed $value, array $attributes){
                if($attributes['info'] === '' || $attributes['info'] === null){return null;}
                $info=json_decode($attributes['info'],true);
                return \Carbon\Carbon::parse($info[0]['start']);
            }
        );
    }
    public function EndDate(): Attribute
    {
        return new Attribute(
            get:function (mixed $value, array $attributes){
                if($attributes['info'] === '' || $attributes['info'] === null){return null;}
                $info=json_decode($attributes['info'],true);
                if($info[0]['end'] === ''){
                    return null;
                }
                return \Carbon\Carbon::parse($info[0]['end']);
            }
        );
    }
}
