<?php

namespace FormItem\Ueditor\Lib;

final class UeditorAuth
{
    private const SESSION_KEY = "ueditor_auth_enabled";

    private function __construct() {}

    /**
     * Enable UEditor access for the current session.
     *
     * Frontend controllers can call this after their own authentication checks.
     */
    public static function enable(): void
    {
        session(self::SESSION_KEY, true);
    }

    public static function disable(): void
    {
        session(self::SESSION_KEY, null);
    }

    public static function canUse(): bool
    {
        $admin_login = function_exists("isAdminLogin") && isAdminLogin();

        return $admin_login || (bool) session(self::SESSION_KEY);
    }
}
