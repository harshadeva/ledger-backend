<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stakeholder extends Model
{
    use HasFactory;
    protected $table = 'stakeholders';

    protected $guarded = [];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'stakeholder_id');
    }
}
