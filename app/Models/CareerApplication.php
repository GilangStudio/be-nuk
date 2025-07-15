<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerApplication extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'applied_at' => 'datetime',
    ];

    // Relationships
    public function position(): BelongsTo
    {
        return $this->belongsTo(CareerPosition::class, 'career_position_id');
    }

    // Accessors
    public function getCvFileUrlAttribute()
    {
        return $this->cv_file_path ? asset('storage/' . $this->cv_file_path) : null;
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Pending Review',
            'reviewed' => 'Reviewed',
            'shortlisted' => 'Shortlisted',
            'rejected' => 'Rejected',
            'hired' => 'Hired',
            default => 'Pending Review'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'reviewed' => 'info',
            'shortlisted' => 'primary',
            'rejected' => 'danger',
            'hired' => 'success',
            default => 'warning'
        };
    }

    public function getAppliedAgoAttribute()
    {
        return $this->applied_at->diffForHumans();
    }

    /**
     * Get human readable time since application
     */
    public function getDaysSinceAppliedAttribute()
    {
        $diffInDays = (int) $this->applied_at->diffInDays(now());
        $diffInHours = (int) $this->applied_at->diffInHours(now());
        $diffInMinutes = (int) $this->applied_at->diffInMinutes(now());

        if ($diffInDays >= 1) {
            return $diffInDays . ' day' . ($diffInDays > 1 ? 's' : '');
        } elseif ($diffInHours >= 1) {
            return $diffInHours . ' hour' . ($diffInHours > 1 ? 's' : '');
        } elseif ($diffInMinutes >= 1) {
            return $diffInMinutes . ' minute' . ($diffInMinutes > 1 ? 's' : '');
        } else {
            return 'Just now';
        }
    }

    /**
     * Get simple days count for admin display
     */
    public function getDaysCountAttribute()
    {
        $days = $this->applied_at->diffInDays(now());
        return $days >= 1 ? $days : 0;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('applied_at', 'desc');
    }
}