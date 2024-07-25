<?php
namespace Modules\User\Controllers;

use Illuminate\Support\Facades\Auth;
use Modules\FrontendController;
use Modules\User\Models\UserWishList;
use Illuminate\Http\Request;
use Modules\User\Models\UserBookmark;

class UserBookmarkController extends FrontendController
{
    protected $userBookmarkClass;
    public function __construct()
    {
        parent::__construct();
        $this->userBookmarkClass = UserBookmark::class;
    }

    // public function index(Request $request){
    //     $bookmark = $this->userBookmarkClass::query()
    //         ->where("user_bookmark.user_id",Auth::id())
    //         ->orderBy('user_bookmark.id', 'desc');
    //     $data = [
    //         'rows' => $bookmark->paginate(5),
    //         'breadcrumbs'        => [
    //             [
    //                 'name'  => __('Bookmark'),
    //                 'class' => 'active'
    //             ],
    //         ],
    //         'page_title'         => __("Bookmark"),
    //     ];
    //     return view('User::frontend.wishList.index', $data);
    // }

    // public function handleBookmark(Request $request){
    //     $object_id = $request->input('object_id');
    //     $object_model = $request->input('object_model');
    //     if(empty($object_id))
    //     {
    //         return $this->sendError(__("Service ID is required"));
    //     }
    //     if(empty($object_model))
    //     {
    //         return $this->sendError(__("Service type is required"));
    //     }
    //     $allServices = get_bookable_services();
    //     if (empty($allServices[$object_model])) {
    //         return $this->sendError(__('Service type not found'));
    //     }
    //     $meta = $this->userBookmarkClass::where("object_id",$object_id)
    //         ->where("object_model",$object_model)
    //         ->where("user_id",Auth::id())
    //         ->first();
    //     if(!empty($meta)){
    //         $meta->delete();
    //         return $this->sendSuccess(['class'=>""]);
    //     }
    //     $meta = new $this->userBookmarkClass($request->input());
    //     $meta->user_id = Auth::id();
    //     $meta->save();
    //     return $this->sendSuccess(['class'=>"active"]);
    // }

    /**
     * Handle untuk bookmark konsep yang sama macam wishlist
     */
    public function handleBookmark(Request $request){
        $object_id = $request->input('object_id');
        $object_model = $request->input('object_model');
        if(empty($object_id))
        {
            return $this->sendError(__("Service ID is required"));
        }
        if(empty($object_model))
        {
            return $this->sendError(__("Service type is required"));
        }
        // $allServices = get_bookable_services();
        // if (empty($allServices[$object_model])) {
        //     return $this->sendError(__('Service type not found'));
        // }
        $meta = $this->userBookmarkClass::where("object_id",$object_id)
            ->where("object_model",$object_model)
            ->where("user_id",Auth::id())
            ->first();
        if(!empty($meta)){
            $meta->delete();
            return $this->sendSuccess(['class'=>""]);
        }
        $meta = new $this->userBookmarkClass($request->input());
        $meta->user_id = Auth::id();
        $meta->save();
        return $this->sendSuccess(['class'=>"active"]);
    }

    public function remove(Request $request){
        $meta = $this->userBookmarkClass::where("object_id",$request->input('id'))
            ->where("object_model",$request->input('type'))
            ->where("user_id",Auth::id())
            ->first();
        if(!empty($meta)){
            $meta->delete();
            return redirect()->back()->with('success', __('Delete success!'));
        }
        return redirect()->back()->with('success', __('Delete fail!'));
    }

    public function check(Request $request)
    {
        $objectId = $request->object_id;
        $userId = $request->user_id;
        $wish = UserBookmark::where([
            'object_id' => $objectId, 
            'user_id' => $userId, 
            'object_model' => 'tour'
        ])->first();
        if($wish){
            return $this->sendSuccess([
                'status' => true
            ]);
        }else{
            return $this->sendSuccess([
                'status' => false
            ]);
        }
    
    }
}
