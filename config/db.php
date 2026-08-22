<?php
// Supabase Direct & Pooler host settings
$hosts = [
    [
        'host' => 'db.xqekzayywscxnqdhkcf.supabase.co',
        'port' => '5432',
        'user' => 'postgres',
    ],
    [
        'host' => 'aws-0-ap-northeast-2.pooler.supabase.com',
        'port' => '6543',
        'user' => 'postgres.xqekzayywscxnqdhkcf',
    ]
];

$password = getenv('DB_PASSWORD') ?: "admin@96#";
$dbname   = getenv('DB_NAME') ?: "postgres";
$conn     = false;

foreach ($hosts as $h) {
    $conn_string = "host={$h['host']} port={$h['port']} dbname={$dbname} user={$h['user']} password={$password} sslmode=require connect_timeout=5";
    $conn = @pg_connect($conn_string);
    if ($conn) {
        break;
    }
}

if (!$conn) {
    echo "Connection failed: " . pg_last_error();
    exit;
}
?>
