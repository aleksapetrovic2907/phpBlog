<?php
namespace Config;

class Database
{
    private static ?\mysqli $connection = null;

    private function __construct()
    {
    }
    private function __clone()
    {
    }

    public static function getConnection(): ?\mysqli
    {
        if (self::$connection === null) {
            // TODO: Get connection details form .env
            $hostName = "localhost";
            $username = "root";
            $password = "";
            $dbName = "phpblog";

            self::$connection = new \mysqli($hostName, $username, $password, $dbName);
            if (self::$connection->connect_error) {
                die("Connection failed: " . self::$connection->connect_error);
            }
        }

        return self::$connection;
    }
}
?>