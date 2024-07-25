<?php

namespace Modules\Api\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Modules\Booking\Models\Booking;
use Illuminate\Http\Request;
use Modules\Location\Models\Location;
use Modules\Tour\Models\Tour;
use Modules\User\Models\UserBookmark;
use Modules\User\Models\UserWishList;

class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['wishlist']]);
    }

    public function getBookingHistory(Request $request)
    {
        $user_id = Auth::id();
        $query = Booking::getBookingHistory($request->input('status'), $user_id);
        $rows = [];
        foreach ($query as $item) {
            $service = $item->service;
            $serviceTranslation = $service->translate();
            $meta_tmp = $item->getAllMeta();
            $item = $item->toArray();
            $meta = [];
            if (!empty($meta_tmp)) {
                foreach ($meta_tmp as $val) {
                    $meta[$val->name] = !empty($json = json_decode($val->val, true)) ? $json : $val->val;
                }
            }
            $item['commission_type'] = json_decode($item['commission_type'], true);
            $item['buyer_fees'] = json_decode($item['buyer_fees'], true);
            $item['booking_meta'] = $meta;
            $item['service_icon'] = $service->getServiceIconFeatured() ?? null;
            $item['service'] = ['title' => $serviceTranslation->title];
            $rows[] = $item;
        }
        return $this->sendSuccess([
            'data' => $rows,
            'total' => $query->total(),
            'max_pages' => $query->lastPage()
        ]);
    }

    public function handleWishlist(Request $request)
    {
        $class = new \Modules\User\Controllers\UserWishListController();
        return $class->handleWishList($request);
        // echo 'test';
    }

    public function indexWishlist(Request $request)
    {
        $query = UserWishList::query()
            ->where("user_wishlist.user_id", Auth::id())
            ->orderBy('user_wishlist.id', 'desc')
            ->paginate(5);
        $rows = [];
        foreach ($query as $item) {
            $service = $item->service;
            if (empty($service)) continue;

            $item = $item->toArray();
            $serviceTranslation = $service->translate();
            // $item['service'] = [
            //     'id'=>$service->id,
            //     'title'=>$serviceTranslation->title,
            //     'price'=>$service->price,
            //     'sale_price'=>$service->sale_price,
            //     'discount_percent'=>$service->discount_percent ?? null,
            //     'image'=>get_file_url($service->image_id),
            //     'content'=>$serviceTranslation->content,
            //     'location' => Location::selectRaw("id,name")->find($service->location_id) ?? null,
            //     'is_featured' => $service->is_featured ?? null,
            //     'service_icon' => $service->getServiceIconFeatured() ?? null,
            //     'review_score' =>  $service->getScoreReview() ?? null,
            //     'service_type' =>  $service->getModelName() ?? null,
            // ];
            // $item['service'] = $service->dataForMobile();
            // $rows[] = $item;
            $rows[] = $service->dataForMobile();
        }
        return $this->sendSuccess(
            [
                'data' => $rows,
                'total' => $query->total(),
                'total_pages' => $query->lastPage(),
            ]
        );
    }

    public function handleBookmark(Request $request)
    {
        $class = new \Modules\User\Controllers\UserBookmarkController();
        return $class->handleBookmark($request);
        // echo 'test';
    }

    public function indexBookmark(Request $request)
    {
        $query = UserWishList::query()
            ->where("user_bookmark.user_id", Auth::id())
            // ->where('object_model', 'bookmark')
            ->orderBy('user_bookmark.id', 'desc')
            ->paginate(5);
        $rows = [];
        foreach ($query as $item) {
            $service = $item->service;
            if (empty($service)) continue;

            $item = $item->toArray();
            $serviceTranslation = $service->translate();
            // $item['service'] = [
            //     'id'=>$service->id,
            //     'title'=>$serviceTranslation->title,
            //     'price'=>$service->price,
            //     'sale_price'=>$service->sale_price,
            //     'discount_percent'=>$service->discount_percent ?? null,
            //     'image'=>get_file_url($service->image_id),
            //     'content'=>$serviceTranslation->content,
            //     'location' => Location::selectRaw("id,name")->find($service->location_id) ?? null,
            //     'is_featured' => $service->is_featured ?? null,
            //     'service_icon' => $service->getServiceIconFeatured() ?? null,
            //     'review_score' =>  $service->getScoreReview() ?? null,
            //     'service_type' =>  $service->getModelName() ?? null,
            // ];
            // $item['service'] = $service->dataForMobile();
            // $rows[] = $item;
            $rows[] = $service->dataForMobile();
        }
        return $this->sendSuccess(
            [
                'data' => $rows,
                'total' => $query->total(),
                'total_pages' => $query->lastPage(),
            ]
        );
    }

    public function bookmarkList(Request $request)
    {
        // $user = User::whereFirebaseToken($request->token)->first();  
        $type = $request->object_id;
        $query = UserBookmark::query()
            ->where("user_bookmark.user_id", Auth::id())
            // ->where('object_model', $type)
            ->orderBy('user_bookmark.id', 'desc')
            ->get();
        $rows = [];

        foreach ($query as $item) {
            $service = $item->service;
            if (empty($service)) continue;
            $item = $item->toArray();
            $rows[] = $service->dataForMobile();
        }
        return $this->sendSuccess(
            [
                'data' => $rows,
                // 'user' => $user
                // 'total'=>$query->total(),
                // 'total_pages'=>$query->lastPage(),
            ]
        );
    }

    public function tourWishlist(Request $request)
    {
        // $user = User::whereFirebaseToken($request->token)->first();
        // $user = User::
        // return Auth::id();
        // $query = UserWishList::query()
        //     ->where("user_wishlist.user_id",Auth::id())
        //     ->orderBy('user_wishlist.id', 'desc') 
        //     // ->withCount('tour.wishlist')           
        //     ->get();  
        $ids = UserWishList::query()
            ->where("user_id", Auth::id())
            // ->orderBy('user_wishlist.id', 'desc')
            // ->withCount('tour.wishlist')           
            ->pluck('object_id');
            // return $ids;
        $tour = Tour::whereIn('id', $ids)->withCount('wishlist')->get();
        $rows = [];
        // foreach ($query as $item) {
        foreach ($tour as $item) {
            // $service = $item->service;
            // if (empty($service)) continue;

            // $item = $item->toArray();
            // $serviceTranslation = $service->translate();
            // $item['service'] = [
            //     'id'=>$service->id,
            //     'title'=>$serviceTranslation->title,
            //     'price'=>$service->price,
            //     'sale_price'=>$service->sale_price,
            //     'discount_percent'=>$service->discount_percent ?? null,
            //     'image'=>get_file_url($service->image_id),
            //     'content'=>$serviceTranslation->content,
            //     'location' => Location::selectRaw("id,name")->find($service->location_id) ?? null,
            //     'is_featured' => $service->is_featured ?? null,
            //     'service_icon' => $service->getServiceIconFeatured() ?? null,
            //     'review_score' =>  $service->getScoreReview() ?? null,
            //     'service_type' =>  $service->getModelName() ?? null,
            // ];
            // $item['service'] = $service->dataForMobile();
            // $rows[] = $item;
            // $rows[] = $service->dataForMobile();
            $rows[] = $item->dataForMobile();
        }
        return $this->sendSuccess(
            [
                'data' => $rows,
                // 'user' => $user
                // 'total'=>$query->total(),
                // 'total_pages'=>$query->lastPage(),
            ]
        );
    }

    public function permanentlyDelete(Request  $request)
    {
        return (new \Modules\User\Controllers\UserController())->permanentlyDelete($request);
    }
}
