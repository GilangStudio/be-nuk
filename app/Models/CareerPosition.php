<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CareerPosition extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'posted_at' => 'date',
        'closing_date' => 'date',
    ];

    // Relationships
    public function applications(): HasMany
    {
        return $this->hasMany(CareerApplication::class);
    }

    // Accessors
    public function getTypeTextAttribute()
    {
        return match($this->type) {
            'full-time' => 'Full-Time',
            'part-time' => 'Part-Time', 
            'contract' => 'Contract',
            'internship' => 'Internship',
            default => 'Full-Time'
        };
    }

    public function getStatusTextAttribute()
    {
        if (!$this->is_active) {
            return 'Inactive';
        }
        
        if ($this->closing_date && $this->closing_date->isPast()) {
            return 'Closed';
        }
        
        return 'Open';
    }

    public function getDaysPostedAttribute()
    {
        return (int) abs($this->posted_at->diffInDays(now()));
    }

    public function getDaysPostedTextAttribute()
    {
        $days = $this->days_posted;
        
        if ($days == 0) {
            return 'Today';
        } elseif ($days == 1) {
            return '1 day ago';
        } elseif ($days < 7) {
            return $days . ' days ago';
        } elseif ($days < 30) {
            $weeks = floor($days / 7);
            return $weeks == 1 ? '1 week ago' : $weeks . ' weeks ago';
        } else {
            $months = floor($days / 30);
            return $months == 1 ? '1 month ago' : $months . ' months ago';
        }
    }

    public function getApplicationsCountAttribute()
    {
        return $this->applications()->count();
    }

    public function getPendingApplicationsCountAttribute()
    {
        return $this->applications()->where('status', 'pending')->count();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOpen($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('closing_date')
                          ->orWhere('closing_date', '>', now());
                    });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('posted_at', 'desc');
    }

    public function scopeApplicable($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('closing_date')
                        ->orWhere('closing_date', '>', now());
                    });
    }

    // Check if position can be deleted
    public function canDelete(): bool
    {
        return $this->applications()->count() === 0;
    }

    /**
     * Check if position is still accepting applications
     */
    public function isAcceptingApplications(): bool
    {
        if (!$this->is_active) {
            return false;
        }
        
        if ($this->closing_date && $this->closing_date->isPast()) {
            return false;
        }
        
        return true;
    }

    // Route key
    public function getRouteKeyName()
    {
        return 'slug';
    }
}