<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateLanguage extends Model
{
    public function templates()
    {
        return $this->hasMany(Template::class);
    }
}
