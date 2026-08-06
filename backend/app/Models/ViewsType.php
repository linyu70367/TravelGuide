<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ViewsType extends Model
{
    protected $table = "views_types";
    protected $primaryKey = 'id';
    protected $fillable = ['typeName'];
    public function views(): HasMany
    {
        return $this->hasMany(Views::class, "typeId");
    }
}
