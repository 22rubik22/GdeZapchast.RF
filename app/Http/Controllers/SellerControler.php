<?php

namespace App\Http\Controllers;

use App\Models\Advert;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SellerControler extends Controller
{
    public function index(Request $request)
    {
        // middleware
        $user = $request->get('seller');

        $id = $user->id;

        $query = Advert::where('user_id', '=', $id)
            ->where('status_ad', 'activ')
            ->where('status_pay', '!=', 'not_pay')
            ->where('status_ad', '!=', 'not_activ')
            ->where('status_ad', '!=', 'arhiv');

        if ($request->has('city') && $request->input('city') !== '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('city', $request->input('city'));
            });
        }

        $adverts = $query->paginate(10);
        //$user->setVisible(['id', 'username', 'avatar_url', 'city', 'email', 'logo_url', 'branches', 'legalInfo']);
        //$user->branches->each->setVisible(['address', 'id_branch']);
        //$user->legalInfo->setVisible(['legal_address', 'organization_name', 'inn', 'kpp']);

        // $cities = User::distinct()->pluck('city')->toArray();

        $usernameCode = Str::slug($user->username);
        return view(
            'adverts.seller_index',
            compact(
                'adverts',
                'id',
                'user',
                'usernameCode'
            )
        );
    }

    public function search(Request $request)
    {
        // middleware
        $user = $request->get('seller');

        $brand = $request->input('brand');

        return [$brand, $user];
    }

}
