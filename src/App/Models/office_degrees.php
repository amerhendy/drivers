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
class office_degrees extends Model
{
    use HasFactory,SoftDeletes,AmerTrait,HasRoles,HasApiTokens,HasUuids;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'office_degrees';
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

    public function FirstDay(): Attribute
    {
        return new Attribute(
            get:function (mixed $value, array $attributes){
                $info=json_decode($attributes['info']);
                if(!count($info)){return false;}
                $info=$info[0];
                return (int) $info->first;
            }
        );
    }
    public function SecondtDay(): Attribute
    {
        return new Attribute(
            get:function (mixed $value, array $attributes){
                $info=json_decode($attributes['info']);
                if(!count($info)){return false;}
                $info=$info[0];
                return (int) $info->second;
            }
        );
    }
    public function LastDay(): Attribute
    {
        return new Attribute(
            get:function (mixed $value, array $attributes){
                $info=json_decode($attributes['info']);
                if(!count($info)){return false;}
                $info=$info[0];
                return (int) $info->last;
            }
        );
    }
}
