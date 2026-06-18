<?php

class DatabasePool
{
    private static array $connections = [];
    private static ?array $env = null;

    public static function connection(string $name = 'default'): PDO
    {
        if (isset(self::$connections[$name])) {
            return self::$connections[$name];
        }

        $config = self::config();
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        self::$connections[$name] = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => true,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$config['charset']} COLLATE utf8mb4_unicode_ci",
        ]);

        return self::$connections[$name];
    }

    public static function query(string $sql, array $params = []): PDOStatement
    {
        $statement = self::connection()->prepare($sql);
        $statement->execute($params);

        return $statement;
    }

    public static function fetch(string $sql, array $params = []): ?array
    {
        $row = self::query($sql, $params)->fetch();

        return $row === false ? null : $row;
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    public static function execute(string $sql, array $params = []): int
    {
        return self::query($sql, $params)->rowCount();
    }

    public static function transaction(callable $callback): mixed
    {
        $pdo = self::connection();

        try {
            $pdo->beginTransaction();
            $result = $callback($pdo);
            $pdo->commit();

            return $result;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $e;
        }
    }

    public static function close(string $name = 'default'): void
    {
        unset(self::$connections[$name]);
    }

    public static function config(): array
    {
        $env = self::env();

        return [
            'host' => $env['DB_HOST'] ?? '127.0.0.1',
            'port' => $env['DB_PORT'] ?? '3306',
            'database' => $env['DB_DATABASE'] ?? 'db_tryout_cpns',
            'username' => $env['DB_USERNAME'] ?? 'root',
            'password' => $env['DB_PASSWORD'] ?? '',
            'charset' => $env['DB_CHARSET'] ?? 'utf8mb4',
        ];
    }

    private static function env(): array
    {
        if (self::$env !== null) {
            return self::$env;
        }

        self::$env = [];
        $path = defined('BASE_PATH') ? BASE_PATH . '/.env' : dirname(__DIR__, 2) . '/.env';

        if (!is_file($path)) {
            return self::$env;
        }

        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            $value = trim($value, "\"'");

            self::$env[$key] = $value;
            $_ENV[$key] = $value;
        }

        return self::$env;
    }
}

function db(string $name = 'default'): PDO
{
    return DatabasePool::connection($name);
}

function pdo(): PDO
{
    return db();
}

function db_query(string $sql, array $params = []): PDOStatement
{
    return DatabasePool::query($sql, $params);
}

function db_fetch(string $sql, array $params = []): ?array
{
    return DatabasePool::fetch($sql, $params);
}

function db_fetch_all(string $sql, array $params = []): array
{
    return DatabasePool::fetchAll($sql, $params);
}

function db_execute(string $sql, array $params = []): int
{
    return DatabasePool::execute($sql, $params);
}

?>
