<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerInfo extends Model
{
    use HasFactory;

    protected $table = 'seller_info';

    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'about_shop',
        'warranty_return_policy',
        'delivery_in_city',
        'delivery_in_city_cost',
        'delivery_to_transport_company',
        'delivery_to_transport_company_cost',
        'delivery_to_route_taxi',
        'delivery_to_route_taxi_cost',
        'delivery_russian_post',
        'russian_post_additional_cost',
        'additional_delivery_conditions',
        'banner_url',
        'branch_ids',
    ];

    protected $casts = [
        'delivery_in_city'              => 'boolean',
        'delivery_to_transport_company' => 'boolean',
        'delivery_to_route_taxi'        => 'boolean',
        'delivery_russian_post'         => 'boolean',
        'delivery_in_city_cost'         => 'integer',
        'delivery_to_route_taxi_cost'   => 'integer',
        'russian_post_additional_cost'  => 'integer',
        'delivery_to_transport_company_cost' => 'integer',
        'branch_ids' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
