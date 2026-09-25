<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Extension extends Model
{
    use GlobalStatus;

    protected $casts = [
        'shortcode' => 'object',
    ];

    protected $hidden = ['script', 'shortcode'];

    public function scopeGenerateScript()
    {
        $script = $this->script;
        foreach ($this->shortcode as $key => $item) {
            $script = str_replace('{{' . $key . '}}', $item->value, $script);
        }
        return $script;
    }

    public function badgeData()
    {
        $html = '';
        if ($this->status == Status::ENABLE) {
            $html = '<span class="text--success border border--success px-2 rounded fs-13">' . trans('Enabled') . '</span>';
        } else {
            $html = '<span class="text--warning border border--warning px-2 rounded fs-13">' . trans('Disabled') . '</span>';
        }
        return $html;
    }
}
