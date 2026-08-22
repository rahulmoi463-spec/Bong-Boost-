<?php
$host = "aws-0-ap-northeast-2.pooler.supabase.com";
$port = "5432"; // Session mode pooler port
$dbname = "postgres";
$user = "postgres.xqekzayywscxnqdhkcf";
$password = getenv('DB_PASSWORD') ?: "admin@96#";

$conn_string = "host={$host} port={$port} dbname={$dbname} user={$user} password={$password} sslmode=require";
$conn = @pg_connect($conn_string);

if (!$conn) {
    // Port 5432 ফেল করলে Port 6543 ট্রাই করবে
    $port = "6543";
    $conn_string = "host={$host} port={$port} dbname={$dbname} user={$user} password={$password} sslmode=require";
    $conn = @pg_connect($conn_string);
}

if (!$conn) {
    die("Database Connection Failed. Please check database password.");
}
?>
