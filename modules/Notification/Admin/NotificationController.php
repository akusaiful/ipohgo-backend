<?php

namespace Modules\Notification\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Modules\AdminController;
use Modules\Core\Models\Device;
// use Modules\Core\Models\Notification;
use Modules\Language\Models\Language;
use Modules\News\Models\NewsCategory;
use Modules\News\Models\News;
use Modules\News\Models\NewsTranslation;
use Modules\Notification\Models\Notification;
use Modules\Notification\Models\NotificationTranslation;

class NotificationController extends AdminController
{
    protected $notification;

    public function __construct()
    {
        $this->setActiveMenu(route('notification.admin.index'));
        $this->notification = Firebase::messaging();
    }

    public function index(Request $request)
    {
        $this->checkPermission('notification_view');
        $dataNotification = Notification::query()->orderBy('id', 'desc');
        $post_name = $request->query('s');
        // $cate = $request->query('cate_id');
        // if ($cate) {
        //    $dataNotification->where('cat_id', $cate);
        // }
        if ($post_name) {
            $dataNotification->where('title', 'LIKE', '%' . $post_name . '%');
            $dataNotification->orderBy('title', 'asc');
        }


        $this->filterLang($dataNotification);

        $data = [
            'rows'        => $dataNotification->with("author")->paginate(20),
            // 'categories'  => NewsCategory::get(),
            'breadcrumbs' => [
                [
                    'name' => __('Notification'),
                    'url'  => route('notification.admin.index')
                ],
                [
                    'name'  => __('All'),
                    'class' => 'active'
                ],
            ],
            "languages" => Language::getActive(false),
            "locale" => \App::getLocale(),
            'page_title' => __("Notification Management")
        ];
        return view('Notification::admin.notification.index', $data);
    }

    public function create(Request $request)
    {
        $this->checkPermission('notification_create');
        $row = new Notification();
        $row->fill([
            'status' => 'publish',
        ]);
        // $row->status = 'publish';
        $data = [
            // 'categories'        => NewsCategory::get()->toTree(),
            'row'         => $row,
            'breadcrumbs' => [
                [
                    'name' => __('Notification'),
                    'url'  => route('notification.admin.index')
                ],
                [
                    'name'  => __('Add Notification'),
                    'class' => 'active'
                ],
            ],
            'translation' => new NotificationTranslation()
        ];
        return view('Notification::admin.notification.detail', $data);
    }

    public function edit(Request $request, $id)
    {
        $this->checkPermission('notification_update');

        $row = Notification::find($id);

        $translation = $row->translate($request->query('lang', get_main_lang()));        

        if (empty($row)) {
            return redirect(route('notification.admin.index'));
        }

        $data = [
            'row'  => $row,
            'translation'  => $translation,
            // 'categories' => NewsCategory::get()->toTree(),
            // 'tags' => $row->getTags(),
            'enable_multi_lang' => true
        ];
        return view('Notification::admin.notification.detail', $data);
    }

    public function store(Request $request, $id)
    {
        if (is_demo_mode()) {
            return redirect()->back()->with('danger', __("DEMO MODE: Disable update"));
        }
        if ($id > 0) {
            $this->checkPermission('notification_update');
            $row = Notification::find($id);
            if (empty($row)) {
                return redirect(route('notification.admin.index'));
            }
        } else {
            $this->checkPermission('notification_create');
            $row = new Notification();
            // $row->status = "publish";
        }

        $validated = $request->validate([
            'title' => 'required',
            'preview' => 'required|max:255',
            'body' => 'required', 
            'status' => 'required'           
        ]);

        $row->fill($request->input());    

        $row->author_id = $request->input('author_id') ?: Auth::id();
        $res = $row->saveOriginOrTranslation($request->query('lang'), true);

        if ($res && $row->status == Notification::STATUS_PUBLISH && !$row->send) {
            $title = $request->input('title');
            $preview = $request->input('preview');

            $message = CloudMessage::fromArray([
                // 'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $preview
                ],
            ]);

            $devices = Device::pluck('fcm_token')->toArray();

            $this->notification->sendMulticast($message, $devices);

            $row->update(['send' => 1]);
        }
        // }






        // $this->notification->send($message);

        if ($id > 0) {
            return back()->with('success',  __('Notification updated'));
        } else {
            return redirect(route('notification.admin.edit', $row->id))->with('success', __('Notification created'));
        }
    }

    public function bulkEdit(Request $request)
    {
        if (is_demo_mode()) {
            return redirect()->back()->with('danger', __("DEMO MODE: Disable update"));
        }
        $this->checkPermission('notification_update');
        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids) or !is_array($ids)) {
            return redirect()->back()->with('error', __('No items selected!'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Please select an action!'));
        }
        if ($action == "delete") {
            foreach ($ids as $id) {
                $query = Notification::where("id", $id);
                if (!$this->hasPermission('notification_manage_others')) {
                    $query->where("create_user", Auth::id());
                    $this->checkPermission('notification_delete');
                }
                $query->first();
                if (!empty($query)) {
                    $query->delete();
                }
            }
        } else {
            foreach ($ids as $id) {
                $query = Notification::where("id", $id);
                if (!$this->hasPermission('notification_manage_others')) {
                    $query->where("create_user", Auth::id());
                    $this->checkPermission('notification_update');
                }
                $query->update(['status' => $action]);
            }
        }
        return redirect()->back()->with('success', __('Update success!'));
    }

    public function trans($id, $locale)
    {
        $row = Notification::find($id);

        if (empty($row)) {
            return redirect()->back()->with("danger", __("Notification does not exists"));
        }

        $translated = Notification::query()->where('origin_id', $id)->where('lang', $locale)->first();
        if (!empty($translated)) {
            redirect($translated->getEditUrl());
        }

        $language = Language::where('locale', $locale)->first();
        if (empty($language)) {
            return redirect()->back()->with("danger", __("Language does not exists"));
        }

        $new = $row->replicate();

        if (!$row->origin_id) {
            $new->origin_id = $row->id;
        }

        $new->lang = $locale;

        $new->save();


        return redirect($new->getEditUrl());
    }
}
