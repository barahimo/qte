<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        function get_limit_pagination(){
            $limit_pagination = 10;
            return $limit_pagination;
        };

        function get_limit_recent(){
            $limit_recent = 5;
            return $limit_recent;
        };

        function get_base64(){
            $base = "data:image/png;base64,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";
            return $base;
        };

        function getPermssion($string)
        {
            $list = [];
            $array = explode("','",$string);
            foreach ($array as $value) 
                foreach (explode("['",$value) as $val) 
                    if($val != '')
                        array_push($list, $val);
            $array = $list;
            $list = [];
            foreach ($array as $value) 
                foreach (explode("']",$value) as $val) 
                    if($val != '')
                        array_push($list, $val);
            return $list;
        }
        function hasPermssion($string)
        {
            $permission = getPermssion(Auth::user()->permission);
            $permission_ = getPermssion(User::find(Auth::user()->user_id)->permission);
            $result = 'no';
            if(
                (Auth::user()->is_admin == 2) ||
                (Auth::user()->is_admin == 1 && in_array($string,$permission)) ||
                (Auth::user()->is_admin == 0 && in_array($string,$permission) && in_array($string,$permission_))
            )
            $result = 'yes';
            return $result;
        }
        function msg($string)
        {
            return $string;
        }
    }
}
