<?php

namespace App\Http\Controllers;

use App\DailyLookup;
use App\Event;
use Illuminate\Http\Request;

class DailyLookups extends Controller
{

    public function store(Request $request)
    {
        $dateString = $request->query('date');
        DailyLookup::store($dateString);
    }

    public function storeMonth(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');
        DailyLookup::storeMonth($month,$year);
    }

    public function storeYear(Request $request)
    {
        $year = $request->query('year');
        DailyLookup::storeYear($year);
    }

    public function storeEverything()
    {
        $start = Event::orderBy('event_time','ASC')->value('event_time');
        $end   = Event::orderBy('event_time','DESC')->value('event_time');

        $startDate = \DateTime::createFromFormat('Y-m-d H:i:s', $start);
        $startYear = intval($startDate->format('Y'));
        $endDate = \DateTime::createFromFormat('Y-m-d H:i:s', $end);
        $endYear = intval($endDate->format('Y'));

        for ($i = $startYear; $i > $endYear; $i++) {
            DailyLookup::storeYear($i);
        }
    }

}
