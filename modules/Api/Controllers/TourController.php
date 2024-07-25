<?php

namespace Modules\Api\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Booking\Models\Service;
use Modules\Flight\Controllers\FlightController;
use Modules\Review\Models\Review;
use Modules\Tour\Models\Tour;
use Modules\Tour\Models\TourCategory;
use Modules\User\Models\UserWishList;
use Traversable;

class TourController extends Controller
{

    public function featured($type = '')
    {
        $tour = Tour::whereIsFeatured(1)->publish()->withCount('wishlist')->get();
        return $this->sendSuccess(
            [
                'total' => $tour->count(),
                'data' => $tour->map(function ($row) {
                    return $row->dataForMobile();
                }),
            ]
        );
    }

    public function like(Tour $tour)
    {
        $totalLike = UserWishList::where(['object_model' => 'tour', 'object_id' => $tour->id])->count();
        return $this->sendSuccess(
            [
                'data' => $totalLike
            ]
        );
    }

    public function review(Tour $tour)
    {
        return $this->sendSuccess(
            [
                'data' => $tour->getReviewDataAttribute()
            ]
        );
    }

    public function reviewList(Request $request, Tour $tour)
    {

        if (!($offset = $request->offset)) {
            $offset = 0;
        }

        $review = Review::where('object_id', $tour->id)
            ->where('object_model', 'tour')
            ->where('status', 'approved')
            ->offset($offset)
            ->limit(20)
            ->latest()->with('author')->get();

        return $this->sendSuccess(
            [
                'data' => $review->map(function ($row) {
                    return $row->dataForMobile();
                })
            ]
        );
    }

    public function reviewRandom()
    {
        $review = Review::where('object_model', 'tour')
            ->where('status', 'approved')
            ->limit(10)
            ->inRandomOrder()
            ->with('author', 'tour')
            ->get();

        foreach ($review as $item) {
            if ($item->tour) {
                $data[] = $item->dataForMobile();
            }
        }

        return $this->sendSuccess(
            [
                'data' => $data
            ]
        );

        // return $this->sendSuccess(
        //     [
        //         'data' => $review->map(function ($row) {
        //             if ($row->tour) {
        //                 // $data = $row->dataForMobile();
        //                 // $data['place'] = $row->tour->dataForMobile();
        //                 return $row->dataForMobile();
        //             }else{
        //                 // return false;
        //             }
        //         })->reject(function ($value) {
        //             // return $value === false;
        //         })
        //     ]
        // );
    }

    /**
     * Popular tour / POI
     */
    public function popular(Request $request)
    {
        if (!($offset = $request->offset)) {
            $offset = 0;
        }

        // $pluck = UserWishList::where(['object_model' => 'tour'])->groupBy('object_id')->limit(5)->pluck('object_id');
        // $tour = Tour::whereIn('id', $pluck)->offset($offset)->limit(5)->orderBy('views', 'desc')->withCount('wishlist')->get();
        $tour = Tour::offset($offset)->limit(5)->publish()->orderBy('views', 'desc')->withCount('wishlist')->get();

        return $this->sendSuccess(
            [
                'total' => $tour->count(),
                'data' => $tour->map(function ($row) {
                    return $row->dataForMobile();
                }),
            ]
        );
    }

    /**
     * Recently added to tour / POI
     */
    public function recently(Request $request)
    {
        if (!($offset = $request->offset)) {
            $offset = 0;
        }

        $tour = Tour::latest()->publish()->offset($offset)->withCount('wishlist')->limit(5)->get();
        return $this->sendSuccess(
            [
                'total' => $tour->count(),
                'data' => $tour->map(function ($row) {
                    return $row->dataForMobile();
                }),
            ]
        );
    }

    public function recommended(Request $request)
    {

        $tour = Tour::inRandomOrder()->publish()->withCount('wishlist')->limit(5)->get();
        return $this->sendSuccess(
            [
                'total' => $tour->count(),
                'data' => $tour->map(function ($row) {
                    return $row->dataForMobile();
                }),
            ]
        );
    }

    public function tourByCategory(Request $request)
    {
        $offset = $request->offset ?: 0;
        $catId = $request->id;

        if ($catId) {
            $tc = TourCategory::whereId($catId)->withCount('tour')->first();
            $tour = Tour::latest()->where('category_id', $catId)->publish()->offset($offset)->limit(5)->get();
            return $this->sendSuccess(
                [
                    'total' => $tour->count(),
                    'name' => $tc->name,
                    'data' => $tour->map(function ($row) {
                        return $row->dataForMobile();
                    }),
                ]
            );
        }
    }

    public function checkLike(Request $request)
    {
        $objectId = $request->object_id;
        $userId = $request->user_id;
        $wish = UserWishList::where([
            'object_id' => $objectId,
            'user_id' => $userId,
            'object_model' => 'tour'
        ])->first();
        if ($wish) {
            return $this->sendSuccess([
                'status' => true
            ]);
        } else {
            return $this->sendSuccess([
                'status' => false
            ]);
        }
    }

    public function list(Request $request)
    {
        $offset = $request->offset ?: 0;
        $limit = $request->limit ?: 5;
        $type = $request->type;
        switch ($type) {
            case 'popular':
                return $this->popular($request);
                break;
            case 'recently':
                return $this->recently($request);
                break;
            case 'recommended':
                return $this->recommended($request);
                break;
            case 'category':
                return $this->recommended($request);
                break;
        }



        // $tour = Tour::latest()->offset($offset)->limit($limit)->get();
        // return $this->sendSuccess(
        //     [
        //         'total' => $tour->count(),
        //         'data' => $tour->map(function ($row) {
        //             return $row->dataForMobile();
        //         }),
        //     ]
        // );
    }

    public function category(Request $request)
    {
        $offset = $request->offset ?: 0;
        $categories = TourCategory::where('parent_id', null)
            ->withCount(['tour' => function($query){
                $query->whereStatus('publish');
            }])
            ->whereStatus('publish')
            ->offset($offset)
            ->limit(5)
            ->get();
        return $this->sendSuccess(
            [
                'total' => $categories->count(),
                'data' => $categories->map(function ($row) {
                    return $row->dataForMobile();
                }),
            ]
        );
    }

    public function search()
    {
        $query = request()->query;
        $tour = Tour::where(['title', 'like', "%$query"])->publish()->get();
        return $this->sendSuccess(
            [
                'total' => $tour->count(),
                'data' => $tour->map(function ($row) {
                    return $row->dataForMobile();
                }),
            ]
        );
    }


    public function searchServices()
    {
        if (!empty(request()->query('limit'))) {
            $limit = request()->query('limit');
        } else {
            $limit = 9;
        }
        $query = new Service();
        $rows = $query->search(request()->input())->paginate($limit);
        $total = $rows->total();
        return $this->sendSuccess(
            [
                'total' => $total,
                'total_pages' => $rows->lastPage(),
                'data' => $rows->map(function ($row) {
                    return $row->dataForApi();
                }),
            ]
        );
    }

    public function getFilters($type = '')
    {
        $type = $type ? $type : request()->get('type');
        if (empty($type)) {
            return $this->sendError(__("Type is required"));
        }
        $class = get_bookable_service_by_id($type);
        if (empty($class) or !class_exists($class)) {
            return $this->sendError(__("Type does not exists"));
        }
        $data = call_user_func([$class, 'getFiltersSearch'], request());
        return $this->sendSuccess(
            [
                'data' => $data
            ]
        );
    }

    public function getFormSearch($type = '')
    {
        $type = $type ? $type : request()->get('type');
        if (empty($type)) {
            return $this->sendError(__("Type is required"));
        }
        $class = get_bookable_service_by_id($type);
        if (empty($class) or !class_exists($class)) {
            return $this->sendError(__("Type does not exists"));
        }
        $data = call_user_func([$class, 'getFormSearch'], request());
        return $this->sendSuccess(
            [
                'data' => $data
            ]
        );
    }

    public function detail($type = '', $id = '')
    {
        if (empty($type)) {
            return $this->sendError(__("Resource is not available"));
        }
        if (empty($id)) {
            return $this->sendError(__("Resource ID is not available"));
        }

        $class = get_bookable_service_by_id($type);
        if (empty($class) or !class_exists($class)) {
            return $this->sendError(__("Type does not exists"));
        }

        $row = $class::find($id);
        if (empty($row)) {
            return $this->sendError(__("Resource not found"));
        }

        if ($type == 'flight') {
            return app()->make(FlightController::class)->getData(\request(), $id);
        }

        return $this->sendSuccess([
            'data' => $row->dataForApi(true)
        ]);
    }

    public function checkAvailability(Request $request, $type = '', $id = '')
    {
        if (empty($type)) {
            return $this->sendError(__("Resource is not available"));
        }
        if (empty($id)) {
            return $this->sendError(__("Resource ID is not available"));
        }
        $class = get_bookable_service_by_id($type);
        if (empty($class) or !class_exists($class)) {
            return $this->sendError(__("Type does not exists"));
        }
        $classAvailability = $class::getClassAvailability();
        $classAvailability = app()->make($classAvailability);
        $request->merge(['id' => $id]);
        if ($type == "hotel") {
            $request->merge(['hotel_id' => $id]);
            return $classAvailability->checkAvailability($request);
        }
        return $classAvailability->loadDates($request);
    }

    public function checkBoatAvailability(Request $request, $id = '')
    {
        if (empty($id)) {
            return $this->sendError(__("Boat ID is not available"));
        }
        $class = get_bookable_service_by_id('boat');
        $classAvailability = $class::getClassAvailability();
        $classAvailability = app()->make($classAvailability);
        $request->merge(['id' => $id]);
        return $classAvailability->availabilityBooking($request);
    }

    public function updateView(Tour $tour)
    {
        $tour->views += 1;
        $tour->update();

        return $this->sendSuccess([
            'data' => $tour
        ]);
    }
}
