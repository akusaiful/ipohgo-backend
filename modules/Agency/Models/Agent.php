<?php

namespace Modules\Agency\Models;

use Illuminate\Http\Request;
use Modules\Booking\Models\Booking;
use Modules\Property\Models\Property;
use Themes\Homez\Agency\Models\Agency;
use Themes\Homez\Review\Models\Review;
use Themes\Homez\User\Models\User;

class Agent extends User
{
    protected $table = 'users';
    protected $reviewClass;
    protected $bookingClass;


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->reviewClass = Review::class;
        $this->bookingClass = Booking::class;

    }

    public function getReviewEnable()
    {
        return setting_item("agent_enable_review", 0);
    }

    public function getReviewApproved()
    {
        return setting_item("agent_review_approved", 0);
    }

    public function review_after_booking(){
        return false;
    }

    public function update_service_rate(){
        $rateData = $this->reviewClass::selectRaw("AVG(rate_number) as rate_total")->where('object_id', $this->id)->where('object_model', 'agent')->where("status", "approved")->first();
        $rate_number = number_format( $rateData->rate_total ?? 0 , 1);
        $this->review_score = $rate_number;
        $this->save();
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'author_id')->where('bc_properties.status', '=', 'publish');
    }

    public function agency()
    {
        return $this->hasOneThrough(Agency::class, AgencyAgent::class, 'agent_id', 'id', 'id', 'agencies_id');
    }

    public function search($request){
        $category_id = $request->query("category_id");
        $location_id = $request->query("location_id");
        $name = $request->query("name");
        $orderBy = $request->query("orderBy");
        $query = $this->query()->where('role_id',2)->with(['properties']);
        if($category_id)
        {
            $query->whereHas('properties',function($q) use($category_id){
                $q->where('category_id', '=', $category_id);
            });
        }
        if($location_id)
        {
            $query->whereHas('properties',function($q) use($location_id){
                $q->where('location_id', '=', $location_id);
            });
        }
        if($name){
            $query->where('name','like','%'.$name.'%');
        }
        switch($orderBy) {
            case 'a-z' :
                $query->orderBy("last_name", "asc");
            case 'z-a' :
                $query->orderBy("last_name", "desc");
            default:
                $query->orderBy("id", "desc");
        }

        return $query;
    }

}
