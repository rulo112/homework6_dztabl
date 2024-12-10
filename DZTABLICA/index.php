<?php
$host = 'localhost';
$dbname = 'lada1';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

$tablesQuery = $pdo->query("SHOW TABLES");
$tables = $tablesQuery->fetchAll(PDO::FETCH_NUM);

echo '<ul>';
foreach ($tables as $table) {
    $tableName = $table[0];
    $columnsQuery = $pdo->query("DESCRIBE `$tableName`");
    $columns = $columnsQuery->fetchAll(PDO::FETCH_ASSOC);
    $columnsCount = count($columns);

    echo "<li><a href='?table=$tableName'>$tableName</a> ($columnsCount)</li>";
}
echo '</ul>';


if (isset($_GET['table'])) {
    $tableName = $_GET['table'];
    $columnsQuery = $pdo->query("DESCRIBE `$tableName`");
    $columns = $columnsQuery->fetchAll(PDO::FETCH_ASSOC);
    $rowsQuery = $pdo->query("SELECT * FROM `$tableName` ORDER BY 1 DESC LIMIT 5");
    $rows = $rowsQuery->fetchAll(PDO::FETCH_ASSOC);

    echo "<h3>Содержимое таблицы: $tableName</h3>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr>";
    foreach ($columns as $column) {
        echo "<th>" . htmlspecialchars($column['Field']) . "</th>";
    }
    echo "</tr>";

    foreach ($rows as $row) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . htmlspecialchars($value) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}
?>