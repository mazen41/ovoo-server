<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class InstalledAddon extends Model
{

    public function statusBadge(): Attribute
    {
        return new Attribute(
            get: fn() => match ($this->status) {
                Status::ADDON_INSTALLED => '<span class="badge badge--success">' . trans('Installed') . '</span>',
                Status::ADDON_UNINSTALLED => '<span class="badge badge--warning">' . trans('Uninstalled') . '</span>',
                default => '<span class="badge badge--secondary">' . trans('Unknown') . '</span>',
            },
        );
    }

    public function scopeInstalled($query)
    {
        return $query->where('status', Status::ADDON_INSTALLED);
    }
}
