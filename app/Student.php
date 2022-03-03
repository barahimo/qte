<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

use function App\Providers\get_limit_pagination;

class Student extends Model
{
    // use SoftDeletes;

    protected $fillable = ["id","cin","nom","prenom","age"];
}
