<?php

namespace App\Models\Subscribers;

use Illuminate\Database\Eloquent\Builder;

trait FilterTrait
{
    public function scopeName(Builder $query, $name)
    {
        return $query->where('name', 'like', "%${name}%");
    }

    public function scopeEmail(Builder $query, $email)
    {
        return $query->where('email', $email);
    }

    public function scopePhone(Builder $query, $phone)
    {
        return $query->where('phone', $phone);
    }

    public function scopeContactId(Builder $query, $contactId)
    {
        return $query->where('contact_id', $contactId);
    }
}
