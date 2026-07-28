<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $fillable = ['name', 'color'];

    public function pages()
    {
        return $this->hasMany(Page::class);
    }
}
