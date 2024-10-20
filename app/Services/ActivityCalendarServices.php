<?php

namespace App\Services;
use App\Models\BoltDriverActivityModel;
use App\Models\ActivityUberModel;

class ActivityCalendarService
{
    public function getCalendarData($fleet)
    {
        $boltActivityModel = new BoltDriverActivityModel();
        $uberActivityModel = new ActivityUberModel();

        $uberActivity = $uberActivityModel->select('datum_unosa')
            ->where('fleet', $fleet)
            ->get()->getResultArray();

        $boltActivity = $boltActivityModel->select('start_date')
            ->where('fleet', $fleet)
            ->get()->getResultArray();

        $uberDates = array_unique(array_column($uberActivity, 'datum_unosa'));
        $boltDates = array_unique(array_column($boltActivity, 'start_date'));
        
        // Optional: Convert to Moment.js format
        $uberDates = array_map(function($date) { return date('Y-m-d', strtotime($date)); }, $uberDates);
        $boltDates = array_map(function($date) { return date('Y-m-d', strtotime($date)); }, $boltDates);

        return ['boltDates' => $boltDates, 'uberDates' => $uberDates];
    }
}