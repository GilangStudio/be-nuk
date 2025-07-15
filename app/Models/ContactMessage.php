<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $guarded = ['id'];

    // Accessors
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'unread' => 'Belum Dibaca',
            'read' => 'Sudah Dibaca',
            'replied' => 'Sudah Dibalas',
            default => 'Belum Dibaca'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'unread' => 'warning',
            'read' => 'info',
            'replied' => 'success',
            default => 'warning'
        };
    }

    public function getCreatedAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    public function scopeReplied($query)
    {
        return $query->where('status', 'replied');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}