<?php


class SessionDatabase implements AppDatabase
{

    private static string $host = "83.98.243.185";
    private static string $db = "nts_site";
    private static string $username = "root";
    private static string $password = "wgnd8b";

    private static $INSTANCE = null;

    private function __construct()
    {
    }

    public static function getInstance(): SessionDatabase
    {
        if (self::$INSTANCE == null)
            self::$INSTANCE = new SessionDatabase();
        return self::$INSTANCE;
    }

    public static function getConnection()
    {
        $conn = null;
        try {
            $conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$db, self::$username, self::$password,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        } catch (PDOExeption $exception) {
//            echo "Conection Error: " . $exception->getMessage();
            exit();
        }
        return $conn;
    }
}