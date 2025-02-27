<?php

namespace App\Admin\Modules\Models;

use App\Admin\Clients\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use SoftDeletes;

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_has_modules', 'module_id', 'client_id');
    }
}
