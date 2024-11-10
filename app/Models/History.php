<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $table = 'history';

    protected $primaryKey = 'history_id';

    // Define fillable attributes
    protected $fillable = [
        'tree_id',
        'user_id',
        'action',
        'old_data',
        'new_data'
    ];
}
