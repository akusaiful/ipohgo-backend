<?php


namespace Themes\Mytravel\Hotel;


use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Modules\Hotel\Hook;
use Modules\Hotel\Models\Hotel;

class ModuleProvider extends ServiceProvider
{
    public function boot(){
        add_action(Hook::FORM_AFTER_POLICY,[$this,'extra_info']);
        add_action(Hook::AFTER_SAVING,[$this,'save_extra_info']);
    }

    public function extra_info(Hotel $hotel){
        $translation = $hotel->translate(\request('lang'));
        echo view("Hotel::admin.hotel.mytravel.badge",['row'=>$hotel,'translation'=>$translation])->render();
    }

    public function save_extra_info(Hotel $hotel,Request $request){
        if($request->input('mytravel_save_extra'))
        {
            $data = [
                'badge_tags'=>$request->input('badge_tags'),
            ];
            if(!is_default_lang($request->query('lang')))
            {
                $translation = $hotel->translation;
                if($translation){
                    $translation->fillByAttr(array_keys($data),$data);
                    $hotel->translation()->save($translation);
                }
            }else{
                $hotel->fillByAttr(array_keys($data),$data);
                $hotel->save();
            }
        }
    }

    public function register()
    {
        $this->app->bind(Hotel::class,\Themes\Mytravel\Hotel\Models\MytravelHotel::class);
    }
}
