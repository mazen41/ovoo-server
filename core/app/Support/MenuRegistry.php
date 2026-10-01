<?php

namespace App\Support;

class MenuRegistry
{
    protected static array $adminMenus = [];
    protected static array $userMenus  = [];

    public static function addAdminMenu(string $section, object $item): void
    {
        static::$adminMenus[$section][] = $item;
    }

    public static function addUserMenu(string $section, object $item): void
    {
        static::$userMenus[$section][] = $item;
    }

    public static function getAdminMenus(): array
    {
        return static::$adminMenus;
    }

    public static function getUserMenus(): array
    {
        return static::$userMenus;
    }
}
