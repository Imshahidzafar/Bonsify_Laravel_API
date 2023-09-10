<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    use HasFactory;
    protected $primaryKey = 'event_tag_id';
    protected $table='event_tags';
    public $timestamps = false;
}
