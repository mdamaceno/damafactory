<?php

namespace App\Support;

class ConnectDatabase
{
	public function setDatabase($driver, $settings)
	{
		\DB::purge(env('DB_CONNECTION'));

		if ($driver === 'mysql') {
			\Config::set('database.connections.mysql', [
				'driver' => 'mysql',
		        'host' => $settings->host,
		        'port' => $settings->port,
		        'database' => $settings->name,
		        'username' => $settings->username,
		        'password' => $settings->password,
		        'unix_socket' => null,
		        'charset' => 'utf8mb4',
		        'collation' => 'utf8mb4_unicode_ci',
		        'prefix' => '',
		        'strict' => true,
		        'engine' => null,
			]);
		}

		\DB::reconnect($driver);
	}

	public function unsetDatabase($driver)
	{
		\DB::purge($driver);
		\DB::setDefaultConnection(env('DB_CONNECTION'));
	}
}