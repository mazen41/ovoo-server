<?php

namespace App\Support;

class MenuManager
{
    public static function getAdminPanelMenu(): object
    {
        $menus      = json_decode(file_get_contents(resource_path('views/admin/partials/menu.json')));
        $addonMenus = MenuRegistry::getAdminMenus();

        foreach ($addonMenus as $section => $items) {
            if (!property_exists($menus, $section)) {
                continue;
            }
            $sectionMenus = $menus->$section;

            foreach ($items as $item) {
                if (property_exists($item, 'position')) {
                    $exists = in_array($item->route_name ?? null, array_column((array) $sectionMenus, 'route_name'), true);
                    if ($exists) {
                        continue;
                    }
                    array_splice($sectionMenus, $item->position, 0, [$item]);
                } else {
                    $sectionMenus[] = $item;
                }
            }
            $menus->$section = $sectionMenus;
        }
        return $menus;
    }

    public static function getUserPanelMenu(): object
    {
        $menus      = json_decode(file_get_contents(resource_path('views/templates/basic/partials/menu.json')));
        $addonMenus = MenuRegistry::getUserMenus();

        foreach ($addonMenus as $section => $items) {
            if (!property_exists($menus, $section)) {
                continue;
            }
            $sectionMenus = $menus->$section;

            foreach ($items as $item) {
                if (property_exists($item, 'position')) {
                    $exists = in_array($item->route_name ?? null, array_column((array) $sectionMenus, 'route_name'), true);
                    if ($exists) {
                        continue;
                    }
                    array_splice($sectionMenus, $item->position, 0, [$item]);
                } else {
                    $sectionMenus[] = $item;
                }
            }
            $menus->$section = $sectionMenus;
        }
        return $menus;
    }
}