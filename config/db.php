<?php
$project_id = "xqekzayywscxlnqdhkcf";
$password   = "BongBoost2026";

$host = "aws-0-ap-northeast-2.pooler.supabase.com";
$port = "6543";
$dbname = "postgres";
$user = "postgres." . $project_id;

// Supabase Connection String
$conn_string = "host='{$host}' port='{$port}' dbname='{$dbname}' user='{$user}' password='{$password}' sslmode='require'";
$conn = @pg_connect($conn_string);

if (!$conn) {
    // Port 5432 চেষ্টা করা হচ্ছে
    $conn_string_alt = "host='{$host}' port='5432' dbname='{$dbname}' user='{$user}' password='{$password}' sslmode='require'";
    $conn = @pg_connect($conn_string_alt);
}

if (!$conn) {
    die("Database Connection Failed. Please try again after 5 minutes.");
}
?>
