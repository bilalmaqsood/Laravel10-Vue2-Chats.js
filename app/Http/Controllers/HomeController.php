<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Repositories\BookingRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $bookingService;

    public function __construct(BookingRepository $booking)
    {
        $this->bookingService = $booking;
    }

    public function index(){
        return view('home');
    }

    public function hotelBookings(Hotel $hotel, Request $request){

        $duration = $request->get('duration');

        $data = $this->bookingService->getFilteredBookings($hotel, $duration);

        return $data;
    }


}
