<?php

namespace App\Support;

class ConnectDatabase
{
    public function setDatabase($driver, $settings)
    {
        if ($driver === 'mysql') {
            \Config::set('database.connections.mysql', [
                'driver' => 'mysql',
                'host' => $settings->host,
                'port' => $settings->port,
                'database' => $settings->database,
                'username' => $settings->username,
                'password' => $settings->password,
                'unix_socket' => null,
                'charset' => $settings->charset,
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => $settings->prefix,
                'strict' => true,
                'engine' => null,
            ]);
        }

        if ($driver === 'firebird') {
            \Config::set('database.connections.firebird', [
                'driver' => 'firebird',
                'host' => $settings->host,
                'port' => $settings->port,
                'database' => $settings->database,
                'username' => $settings->username,
                'password' => $settings->password,
                'unix_socket' => null,
                'charset' => $settings->charset,
            ]);
        }

        \DB::purge(env('DB_CONNECTION'));
        \DB::reconnect($driver);
    }

    public function unsetDatabase($driver)
    {
        \DB::purge($driver);
        \DB::reconnect($driver);
    }
}
