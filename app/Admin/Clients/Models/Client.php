<?php

namespace App\Admin\Clients\Models;

use App\Admin\Modules\Models\Module;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    public function modules()
    {
        return $this->belongsToMany(
            Module::class, // Related Model
            'client_has_modules', // Pivot Table Name
            'client_id', // Foreign Key for Clients
            'module_id' // Foreign Key for Modules
        );
    }
}
