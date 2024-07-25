<?php

namespace Modules\Dashboard\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Modules\AdminController;
use Modules\Booking\Models\Booking;
use Modules\Core\Models\Device;
use Modules\Media\Models\MediaFile;
use Modules\Notification\Models\Notification;
use Modules\Review\Models\Review;
use Modules\Tour\Models\Tour;

class DashboardController extends AdminController
{
    public function index()
    {
        $f = strtotime('monday this week');
        $data = [
            // 'recent_bookings'    => Booking::getRecentBookings(),
            'recent_reviews' => Review::limit(10)->latest()->get(),
            'users' => User::limit(10)->latest()->get(),
            'total_user' => User::count(),
            'total_poi' => Tour::whereStatus('publish')->count(),
            'total_review' => Review::countReviewByStatus('approved'),
            'total_review_spam' => Review::countReviewByStatus('spam'),
            'total_device' => Device::count(),
            'total_media' => MediaFile::whereDriver('uploads')->count(),
            'total_notification' => Notification::whereSend(1)->count(),
            'top_cards'          => Booking::getTopCardsReport(),
            'earning_chart_data' => Booking::getDashboardChartData($f, time())
        ];
        return view('Dashboard::index', $data);
    }

    public function reloadChart(Request $request)
    {
        $chart = $request->input('chart');
        switch ($chart) {
            case "earning":
                $from = $request->input('from');
                $to = $request->input('to');
                return $this->sendSuccess([
                    'data' => Booking::getDashboardChartData(strtotime($from), strtotime($to))
                ]);
                break;
        }
    }
}
