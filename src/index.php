<?php

$appTitle = getenv("APP_TITLE") ?: "Request Logger App";
$logFile = getenv("LOG_FILE") ?: "/app/logs/requests.log";

if (!is_dir(dirname($logFile))) {
    mkdir(dirname($logFile), 0777, true);
}

$ip = $_SERVER["REMOTE_ADDR"];
$time = date("Y-m-d H:i:s");
$method = $_SERVER["REQUEST_METHOD"];

$log = "$time | IP: $ip | METHOD: $method\n";

file_put_contents($logFile, $log, FILE_APPEND);

$logs = [];

if (file_exists($logFile)) {
    $logs = file($logFile);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $appTitle; ?></title>
</head>
<body>

<h1><?php echo $appTitle; ?></h1>

<p>Every page refresh creates a new log entry.</p>

<h2>Logs</h2>

<pre>
<?php
foreach ($logs as $line) {
    echo $line;
}
?>
</pre>

</body>
</html>