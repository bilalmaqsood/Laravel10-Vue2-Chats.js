<?php

namespace App\Repositories;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class BookingRepository
{
    public function getFilteredBookings($hotel, $duration){

        list($startDate, $endDate) = $this->getDateRange($duration);

        $bookings = $hotel->bookings()->whereCreatedDateBetween($startDate->getTimestamp(), $endDate->getTimestamp())->get();

        list($data, $totalAmount) = $this->parseBookings($bookings, $startDate, $endDate, $duration);

        return ["data" => $data, "totalAmount" => $totalAmount];
    }

    public function getDateRange($duration){


        $startDate = now();
        $endDate = now();

        if ($duration == 'current_week'){
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
        }else if ($duration == 'current_month'){
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        }else if($duration == 'last_month'){
            $startDate = new Carbon('first day of last month');
            $endDate = new Carbon('last day of last month');
        }else if($duration == 'current_year'){
            $startDate = Carbon::parse(mktime(0, 0, 0, 1, 1, date("Y")));
            $endDate   = Carbon::parse(mktime(0, 0, 0, 12, 31, date("Y")));
        }else if($duration == 'last_year'){
            $startDate = Carbon::parse(mktime(0, 0, 0, 1, 1, date("Y") - 1));
            $endDate   = Carbon::parse(mktime(0, 0, 0, 12, 31, date("Y") - 1));
        }

        return [$startDate, $endDate];
    }

    public function parseBookings($bookings, $startDate, $endDate, $duration){
        $totalAmount = 0;
        $datesArray = [];
        $weeksArray = [];
        $weekSum = 0;
        $previousWeek = null;
        $weekStartDate = null;
        $weekEndDate = null;

        $data = [];

        $dates = CarbonPeriod::create($startDate, $endDate)->toArray();

        foreach ($dates as $date) array_push($datesArray, $date->toDateString());

        $datesArray = array_flip($datesArray);
        $datesArray = array_fill_keys(array_keys($datesArray), 0);

        foreach ($bookings as $booking){
            $totalAmount += $booking->total_grand;
            $bookingDate = Carbon::parse($booking->date_created)->toDateString();
            $datesArray[$bookingDate] += $booking->total_grand;
        }

        if($duration == 'current_week'){
            foreach ($datesArray as $key => $value){
                array_push($data, [ "x" => Carbon::parse($key)->format('jS F'), "y" => ceil($value)]);
            }
        }else{
            foreach ($datesArray as $key => $value){
                $weekNumber = Carbon::parse($key)->week();

                if(!$previousWeek){
                    $previousWeek = $weekNumber;
                    $weekStartDate =  Carbon::parse($key)->format('jS F');
                }

                if ($previousWeek == $weekNumber){
                    $weekEndDate =  Carbon::parse($key)->format('jS F');
                    $weekSum += ceil($value);

                } else {
                    array_push($data, [ "x" => "$weekStartDate - $weekEndDate", "y" => ceil($weekSum)]);

                    $weekSum = 0;
                    $weekStartDate =  Carbon::parse($key)->format('jS F');
                    $weekEndDate =  Carbon::parse($key)->format('jS F');
                    $weekSum += ceil($value);
                    $previousWeek = $weekNumber;

                }
            }
        }




        return [$data, ceil($totalAmount)];
    }
}
