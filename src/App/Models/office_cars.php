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
class office_cars extends Model
{
    use HasFactory,SoftDeletes,AmerTrait,HasRoles,HasApiTokens,HasUuids;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'office_cars';
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
    public function number(): Attribute
    {
        return new Attribute(
            get:function (mixed $value, array $attributes){
                $info=json_decode($attributes['info']);
                $info=collect($info);

                $info=$info->last(function ($value, $key) {
                    return $value->end == '';
                });
                return $info->number;
            }
        );
    }
    public function startDate(): Attribute
    {
        return new Attribute(
            get:function (mixed $value, array $attributes){
                $info=json_decode($attributes['info']);
                $info=collect($info);
                $info=$info->last(function ($value, $key) {
                    return $value->end == '';
                });
                return $info->start;
            }
        );
    }
    public function endDate(): Attribute
    {
        return new Attribute(
            get:function (mixed $value, array $attributes){
                $info=json_decode($attributes['info']);
                $info=collect($info);

                $info=$info->last(function ($value, $key) {
                    return $value->end == '';
                });
                return $info->end;
            }
        );
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
