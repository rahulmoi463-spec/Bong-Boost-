<?php
$host = getenv('DB_HOST') ?: "aws-0-ap-northeast-2.pooler.supabase.com";
$port = getenv('DB_PORT') ?: "6543";
$dbname = getenv('DB_NAME') ?: "postgres";
$user = getenv('DB_USER') ?: "postgres.xqekzayywscxnqdhkcf";
$password = getenv('DB_PASSWORD') ?: "admin@96#";

$conn_string = "host={$host} port={$port} dbname={$dbname} user={$user} password={$password} sslmode=require options='--options=endpoint=xqekzayywscxnqdhkcf'";
$conn = @pg_connect($conn_string);

if (!$conn) {
    die("Database Connection Failed");
}
?>
