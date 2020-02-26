<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SellerDetail extends Model
{
    protected $fillable = ['user_id','nid','tin', 'company_name', 'bkash', 'rocket'];
}
