<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountsTransaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeCredit($query)
    {
        return $query->where('type', 'credit');
    }

    public function scopeDebit($query)
    {
        return $query->where('type', 'debit');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class, 'stakeholder_id');
    }
}
