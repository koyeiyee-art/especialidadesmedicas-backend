<?php
// Debug file to test if PHP is working
echo "<h1>PHP is working!</h1>";
echo "<p>Server Time: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// Test environment variables
echo "<h2>Environment Check:</h2>";
echo "<p>MYSQL_PUBLIC_URL exists: " . (getenv('MYSQL_PUBLIC_URL') ? 'YES' : 'NO') . "</p>";

// Test file structure
echo "<h2>Files in current directory:</h2>";
echo "<pre>";
$files = scandir('.');
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        echo $file . "\n";
    }
}
echo "</pre>";

// Test database connection
echo "<h2>Database Connection Test:</h2>";
try {
    $mysql_url = getenv('MYSQL_PUBLIC_URL') ?: 'mysql://root:IvXqoRMzqDxfgYmnKmXsQauIgURfoGwH@interchange.proxy.rlwy.net:33933/railway';
    $url_parts = parse_url($mysql_url);
    
    $host = $url_parts['host'] . ':' . $url_parts['port'];
    $user = $url_parts['user'];
    $pass = $url_parts['pass'];
    $dbname = ltrim($url_parts['path'], '/');
    
    echo "<p>Attempting to connect to: $host</p>";
    echo "<p>Database: $dbname</p>";
    
    $conn = new mysqli($host, $user, $pass, $dbname);
    
    if ($conn->connect_error) {
        echo "<p style='color: red;'>Connection FAILED: " . $conn->connect_error . "</p>";
    } else {
        echo "<p style='color: green;'>Connection SUCCESSFUL!</p>";
        $conn->close();
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
