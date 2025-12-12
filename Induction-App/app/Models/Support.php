<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'status', 'ticket_no','department','subject', 'description','attachment'];

    public function messages()
    {
        return $this->hasMany(SupportMessage::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
