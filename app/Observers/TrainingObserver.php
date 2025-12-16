<?php

namespace App\Observers;

use App\Models\Training;
use Carbon\Carbon;

class TrainingObserver
{
    public function retrieved(Training $training)
    {
        // Auto set is_active = false if end_date passed
        if ($training->end_date && Carbon::parse($training->end_date)->endOfDay()->isPast()) {
            if ($training->is_active) {
                $training->is_active = false;
                $training->saveQuietly();
            }
        }
    }
}