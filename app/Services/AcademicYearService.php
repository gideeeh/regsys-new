<?php
namespace App\Services;

use App\Models\Academic_Year;
use Carbon\Carbon;

class AcademicYearService
{
    /**
     * Determine the currently active academic year based on today's date.
     *
     * @return Academic_Year|null
     */
    public function determineActiveAcademicYear(): ?Academic_Year
    {
        $today = Carbon::today();

        $activeAcadYear = Academic_Year::where('term_1_start', '<=', $today)
                                        ->where(function ($query) use ($today) {
                                            $query->where('term_3_end', '>=', $today)
                                                  ->orWhere(function ($q) use ($today) {
                                                      // This accounts for academic years where term 3 might not be set
                                                      $q->where('term_3_end', null)
                                                        ->where('term_2_end', '>=', $today);
                                                  });
                                        })
                                        ->first();

        return $activeAcadYear;
    }
}
