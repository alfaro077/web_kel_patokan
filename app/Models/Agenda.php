<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = [
        'title',
        'description',
        'agenda_date',
        'agenda_end_date',
        'agenda_time',
        'agenda_end_time',
        'location',
        'is_active',
    ];

    protected $casts = [
        'agenda_date' => 'date',
        'agenda_end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Otomatis non-aktifkan agenda yang sudah lewat tanggalnya.
     */
    public static function autoArchivePastAgendas()
    {
        self::where('is_active', true)
            ->where(function ($q) {
                $q->whereNotNull('agenda_end_date')->whereDate('agenda_end_date', '<', now())
                  ->orWhere(function ($sub) {
                      $sub->whereNull('agenda_end_date')->whereDate('agenda_date', '<', now());
                  });
            })->update(['is_active' => false]);
    }
}
