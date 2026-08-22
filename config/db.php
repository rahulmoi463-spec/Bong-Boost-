<?php
$host = "aws-0-ap-northeast-2.pooler.supabase.com";
$port = "6543";
$dbname = "postgres";
$user = "postgres.xqekzayywscxlnqdhkcf";
$password = "Bongboostdb#96";

$conn_string = "host={$host} port={$port} dbname={$dbname} user={$user} password={$password} sslmode=require";
$conn = @pg_connect($conn_string);

if (!$conn) {
    // Port 5432 Direct Try
    $conn_string = "host=db.xqekzayywscxlnqdhkcf.supabase.co port=5432 dbname={$dbname} user=postgres password={$password} sslmode=require";
    $conn = @pg_connect($conn_string);
}

if (!$conn) {
    die("Database Connection Failed");
}
?>
