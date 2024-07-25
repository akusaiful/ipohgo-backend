<?php
namespace Themes\Mytravel\Car;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Modules\Car\Hook;
use Modules\Car\Models\Car;
use Modules\Car\Models\CarTranslation;

class ModuleProvider extends ServiceProvider
{
    public function boot(){
        add_action(Hook::FORM_AFTER_SERVICE_FEE,[$this,'extra_info']);
        add_action(Hook::AFTER_SAVING,[$this,'save_extra_info']);
    }

    public function extra_info(Car $car){
        $translation = $car->translate(\request('lang'));
        echo view("Car::admin.car.mytravel.specifications",['row'=>$car,'translation'=>$translation])->render();
    }

    public function save_extra_info(Car $car,Request $request){
        if($request->input('mytravel_save_extra'))
        {
            $data = [
                'specifications'=>$request->input('specifications'),
            ];
            if(!is_default_lang($request->query('lang')))
            {
                $translation = $car->translation;
                if($translation){
                    $translation->fillByAttr(array_keys($data),$data);
                    $car->translation()->save($translation);
                }
            }else{
                $car->fillByAttr(array_keys($data),$data);
                $car->save();
            }
        }
    }
    public function register()
    {
        $this->app->bind(Car::class,\Themes\Mytravel\Car\Models\MytravelCar::class);
        $this->app->bind(CarTranslation::class,\Themes\Mytravel\Car\Models\MytravelCarTranslation::class);
    }
}
