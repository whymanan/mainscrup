<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sms extends Model {
    protected $table = 'sms_details';

    protected $fillable = [
        'sms_to',
        'template'
    ];
}
