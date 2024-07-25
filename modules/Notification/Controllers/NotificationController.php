<?php
namespace Modules\Notification\Controllers;

use Illuminate\Http\Request;
use Modules\FrontendController;
use Modules\Language\Models\Language;
use Modules\News\Models\News;
use Modules\News\Models\NewsCategory;
use Modules\News\Models\NewsTranslation;
use Modules\News\Models\Tag;
use Modules\Notification\Models\Notification;

class NotificationController extends FrontendController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $layout = setting_item("notification_layout_search", 'normal');
        if ($request->query('_layout')) {
            $layout = $request->query('_layout');
        }
        $model_Notification = Notification::query()->select("bravo_notifications.*");
        $model_Notification->where("bravo_notifications.status", "publish")->orderBy('bravo_notifications.id', 'desc');
        if (!empty($search = $request->input("s"))) {
            $model_Notification->where(function($query) use ($search) {
                $query->where('bravo_notifications.title', 'LIKE', '%' . $search . '%');
                $query->orWhere('bravo_notifications.body', 'LIKE', '%' . $search . '%');
            });

            if( setting_item('site_enable_multi_lang') && setting_item('site_locale') != app_get_locale() ){
                $model_Notification->leftJoin('core_notification_translations', function ($join) use ($search) {
                    $join->on('bravo_notifications.id', '=', 'core_notification_translations.origin_id');
                });
                $model_Notification->orWhere(function($query) use ($search) {
                    $query->where('core_notification_translations.title', 'LIKE', '%' . $search . '%');
                    $query->orWhere('core_notification_translations.body', 'LIKE', '%' . $search . '%');
                });
            }

            $title_page = __('Search results : ":s"', ["s" => $search]);
        }
        $data = [
            'rows'              => $model_Notification->with("author")->with('translation')->paginate(5),
            // 'model_category'    => NewsCategory::query()->where("status", "publish"),
            // 'model_tag'         => Tag::query(),
            'model_notification'        => Notification::query()->where("status", "publish"),
            'custom_title_page' => $title_page ?? "",
            'breadcrumbs'       => [
                [
                    'name'  => __('Notification'),
                    'url'  => route('notification.index'),
                    'class' => 'active'
                ],
            ],
            "seo_meta" => Notification::getSeoMetaForPageList(),
            "languages"=>Language::getActive(false),
            "locale"=> app()->getLocale(),
            'header_transparent'=>true,
            'layout'=>$layout
        ];
        return view('Notification::frontend.index', $data);
    }

    public function detail(Request $request, $slug)
    {
        $row = News::where('id', $slug)->where('status','publish')->first();
        if (empty($row)) {
            return redirect('/');
        }
        $translation = $row->translate();

        // if (!empty($cat_id = $row->cat_id)) {
        //     $related = News::where('cat_id', $cat_id)->where("status","publish")->take(4)->whereNotIn('id', [$row->id])->with(['translation'])->get();
        // }

        $data = [
            'row'               => $row,
            'translation'       => $translation,
            // 'model_category'    => NewsCategory::where("status", "publish"),
            // 'model_tag'         => Tag::query(),
            'model_notifications'        => Notification::where("status", "publish"),
            'custom_title_page' => $title_page ?? "",
            'related'           => $related ?? false,
            'breadcrumbs'       => [
                [
                    'name' => __('Notification'),
                    'url'  => route('notification.index')
                ],
                [
                    'name'  => $translation->title,
                    'class' => 'active'
                ],
            ],
            'seo_meta'  => $row->getSeoMetaWithTranslation(app()->getLocale(),$translation),
        ];
        $this->setActiveMenu($row);
        return view('Notification::frontend.detail', $data);
    }
}
