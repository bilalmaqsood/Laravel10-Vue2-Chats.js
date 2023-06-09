<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Repositories\BookingRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $booking;

    public function __construct(BookingRepository $booking)
    {
        $this->booking = $booking;
    }

    public function index(){
        return view('home');
    }

    public function hotelBookings(Hotel $hotel, Request $request){

        $duration = $request->get('duration');

        $data = $this->booking->getFilteredBookings($hotel, $duration);

        return $data;
    }


}
