<?php


namespace Themes\Mytravel\Tour;


use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Modules\Tour\Hook;
use Modules\Tour\Models\Tour;

class ModuleProvider extends ServiceProvider
{

    public function boot(){
        add_action(Hook::FORM_AFTER_MAX_PEOPLE,[$this,'tour_extra_info']);
        add_action(Hook::AFTER_SAVING,[$this,'save_tour_extra_info']);
    }

    public function tour_extra_info(Tour $tour){
        echo view("Tour::admin.tour.extra_mytravel",['row'=>$tour])->render();
    }

    public function save_tour_extra_info(Tour $tour,Request $request){
        if($request->input('mytravel_save_extra'))
        {
            if(is_default_lang($request->query('lang'))) {
                $data = [
                    'date_form_to' => $request->input('date_form_to'),
                    'min_age' => $request->input('min_age'),
                    'pickup' => $request->input('pickup'),
                    'wifi_available' => $request->input('wifi_available'),
                    'paid_parking' => $request->input('paid_parking'),
                    'free_parking' => $request->input('free_parking'),
                ];
                $tour->fillByAttr(array_keys($data), $data);
                $tour->save();
            }
        }
    }
}
