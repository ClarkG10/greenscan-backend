<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tree extends Model
{
    use HasFactory;

    protected $table = 'trees';

    protected $primaryKey = 'tree_id';

    protected $fillable = [
        'common_name',
        'scientific_name',
        'family_name',
        'economic_use',
        'iucn_status',
        'dbh',
        'dab',
        't_height',
        'tree_volume',
        'biomass',
        'carbon_stored',
        'age',
        'tree_health',
        'price',
        'longitude',
        'latitude',
        'user_id',
    ];

    protected $casts = [
        'dbh' => 'float',
        'dab' => 'float',
        't_height' => 'float',
        'tree_volume' => 'float',
        'biomass' => 'float',
        'carbon_stored' => 'float',
        'age' => 'integer',
        'longitude' => 'decimal:7',
        'latitude' => 'decimal:7',
    ];
}
