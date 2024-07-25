<?php
namespace Modules\Api\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\News\Models\News;
use Modules\News\Models\NewsCategory;
use Modules\Notification\Models\Notification;

class NotificationController extends Controller
{

    public function search(Request $request){
        $offset = $request->offset?: 0;
        $model_Notification = Notification::query()->select("bravo_notifications.*");    
        $model_Notification->where("bravo_notifications.status", "publish")->orderBy('bravo_notifications.id', 'desc');
        if (!empty($search = $request->query("s"))) {
            $model_Notification->where(function($query) use ($search) {
                $query->where('bravo_notifications.title', 'LIKE', '%' . $search . '%');
                $query->orWhere('bravo_notifications.body', 'LIKE', '%' . $search . '%');
            });

            if( setting_item('site_enable_multi_lang') && setting_item('site_locale') != app_get_locale() ){
                $model_Notification->leftJoin('bravo_notification_translations', function ($join) use ($search) {
                    $join->on('bravo_notifications.id', '=', 'bravo_notification_translations.origin_id');
                });
                $model_Notification->orWhere(function($query) use ($search) {
                    $query->where('bravo_notification_translations.title', 'LIKE', '%' . $search . '%');
                    $query->orWhere('bravo_notification_translations.content', 'LIKE', '%' . $search . '%');
                });
            }
        }
        if($cat_id = $request->query('cat_id')){
            $model_Notification->where('cat_id',$cat_id);
        }
       
        $model_Notification->offset($offset);
        // $rows = $model_Notification->with("author")->with('translation')->with("category")->paginate(2);
        $rows = $model_Notification->with("author")->with('translation')->limit(5)->get();
        $total = $rows->count();
        return $this->sendSuccess(
            [
                'total'=>$total,
                // 'total_pages'=>$rows->lastPage(),
                'data'=>$rows->map(function($row){
                    return $row->dataForMobile();
                }),
            ]
        );
    }
    public function category(Request $request){
        $model_Notification = NewsCategory::query()->select("bravo_notification_category.*");
        $model_Notification->where("bravo_notification_category.status", "publish");
        if (!empty($search = $request->query("s"))) {
            $model_Notification->where(function($query) use ($search) {
                $query->where('bravo_notification_category.name', 'LIKE', '%' . $search . '%');
            });

            if( setting_item('site_enable_multi_lang') && setting_item('site_locale') != app_get_locale() ){
                $model_Notification->leftJoin('bravo_notification_category_translations', function ($join) use ($search) {
                    $join->on('bravo_notification_category.id', '=', 'bravo_notification_category_translations.origin_id');
                });
                $model_Notification->orWhere(function($query) use ($search) {
                    $query->where('bravo_notification_category_translations.title', 'LIKE', '%' . $search . '%');
                });
            }
        }
        $rows = $model_Notification->with('translation')->get()->toTree();
        return $this->sendSuccess(
            [
                'data'=>$rows->map(function($row){
                    return $row->dataForApi();
                }),
            ]
        );
    }

    public function detail($id = '')
    {
        $row = Notification::find($id);
        if(empty($row)){
            return $this->sendError(__("Notification not found"));
        }
        return $this->sendSuccess([
            'data'=>$row->dataForMobile(true)
        ]);
    }
}
