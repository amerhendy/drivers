<?php
namespace Amerhendy\Drivers;
use Illuminate\Support\Facades\Route;
use DateTime;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
class DriversServiceProvider extends ServiceProvider
{
    use \Amerhendy\Amer\App\Helpers\Library\Database\PublishesMigrations;
    protected $defer = false;
    public $pachaPath="Amerhendy\Drivers\\";
    public $startcomm="AmerDriver";
    public function register(): void
    {
        //require_once __DIR__.'/macro.php';
    }
    public function boot(Router $router): void
    {
        //dd(config('Amer'));
        $path=cleanDir(base_path('vendor/AmerHendy/Drivers/src/'));
        $this->loadConfigs();
        $this->registerMigrations(cleanDir($path.'/database/migrations'));
        $this->loadroutes($this->app->router);
        $this->loadTranslationsFrom(__DIR__.'/Lang','ODLANG');
        $this->loadViewsFrom($path.'/view', 'Drivers');
        //$this->addmainmenu();
        $this->publishFiles();
        $this->disk();
        $this->temperoryurl();
    }
    public function disk(){
        app()->config['filesystems.disks.'.config('Amer.Drivers.root_disk_name')] = [
            'driver'=>'local',
            'root' => storage_path('app/Drivers'),
            'url' => env('APP_URL').'/storage/Amer/Drivers/',
            'visibility' => 'public',
        ];
    }
    public function temperoryurl(){
        Storage::disk(config('Amer.Drivers.root_disk_name'))->buildTemporaryUrlsUsing(
            function (string $path, DateTime $expiration, array $options) {
                return URL::temporarySignedRoute(
                    'local.temp',
                    $expiration,
                    array_merge($options, ['path' => $path])
                );
            }
        );
    }
    public function loadConfigs(){

        foreach(getallfiles(cleanDir(__DIR__.'/config/Amer')) as $file){
            $con=Str::replace(DIRECTORY_SEPARATOR,'.',Str::afterLast(Str::remove('.php',$file),'config'.DIRECTORY_SEPARATOR));
            $this->mergeConfigFrom($file,$con);
        }
    }
    public function loadroutes(Router $router)
    {
        $packagepath=base_path('vendor/AmerHendy/Drivers/src/');
        $routepath=$this->getallfiles($packagepath.'/Route/');
        foreach($routepath as $path){
            //$this->loadRoutesFrom($path);
        }
        $this->loadRoutesFrom($packagepath.'/Route/route.php');
        Route::group($this->apirouteConfiguration(), function () use($packagepath){
            $this->loadRoutesFrom($packagepath.'/Route/api.php');
        });
    }
    protected function apirouteConfiguration()
    {
        return [
            'prefix' => 'api/v1/DriversApi',
            'middleware' => 'client',
            'name'=>config('Amer.Drivers.routeName_prefix','DriversApi'),
            'namespace'  =>config('Amer.Drivers.Controllers','\\Amerhendy\Drivers\App\Http\Controllers\\'),
        ];
    }
    public function addmainmenu(){
        $sidelayout_path=resource_path('views/vendor/Amer/Base/inc/menu/mainmenu.blade.php');
        $file_lines=File::lines($sidelayout_path);
        if(!$this->getLastLineNumberThatContains("Route('Mosama.index')",$file_lines->toArray())){
            $newlines=[];
            $newlines[]="@if(Auth::guard('Employers'))";
            $newlines[]="<!-- {{Route('Mosama.index')}} --><li class=\"nav-item\"><a href=\"{{Route('Mosama.index')}}\" class=\"rounded nav-link\"><span class=\"fab fa-servicestack\"></span>{{trans('\ANG::Mosama_JobTitles.Mosama_JobTitles')}}</a></li>";
            $newlines[]='@endif';
            $newarr=array_merge($file_lines->toArray(),$newlines);
            $new_file_content = implode(PHP_EOL, $newarr);
            File::put($sidelayout_path,$new_file_content);
        }
    }
    public function getLastLineNumberThatContains($needle, $haystack,$skipcomment=false)
    {
        $matchingLines = array_filter($haystack, function ($k) use ($needle,$skipcomment) {
            if($skipcomment == true){
                if(!Str::startsWith(trim($k),'//')){
                    return strpos($k, $needle) !== false;
                }
            }else{
                    return strpos($k, $needle) !== false;
            }

        });
        if ($matchingLines) {
            return array_key_last($matchingLines);
        }

        return false;
    }
    function publishFiles()  {
        $pb=config('Amer.Drivers.package_path') ?? __DIR__;
        $config_files = [$pb.'/config/Amer' => config_path('Amer')];
        $public_assets = [cleanDir([$pb,'public']) => config('Amer.Amer.public_path')];
        $this->publishes($public_assets, $this->startcomm.':public');
        //$this->publishes($config_files, 'Drivers:config');
    }
    function getallfiles($path){
        $files = array_diff(scandir($path), array('.', '..'));
        $out=[];
        foreach($files as $a=>$b){
            if(is_dir($path."/".$b)){
                $out=array_merge($out,getallfiles($path."/".$b));
            }else{
                $ab=Str::after($path,'/vendor');
                $ab=Str::replace('//','/',$ab);
                $ab=Str::finish($ab,'/');
                $out[]=$ab.$b;
            }
        }
        return $out;
    }
}
