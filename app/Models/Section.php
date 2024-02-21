<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $table = 'sections';
    protected $primaryKey = 'section_id';
    protected $fillable = [
        'section_name',
        'subject_id',
        'academic_year',
        'term',
        'is_openedUponRequest',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function enrolledSubjects()
    {
        return $this->hasMany(Enrolled_Subject::class, 'section_id');
    }

    public function sectionSchedules()
    {
        return $this->hasMany(Section_Schedule::class, 'section_id');
    }
}
