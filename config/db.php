<?php
$host = "aws-0-ap-northeast-2.pooler.supabase.com";
$port = "6543";
$dbname = "postgres";
$user = "postgres.xqekzayywscxnqdhkcf";
$password = "Bongboostdb#96";

// পাসওয়ার্ডে থাকা বিশেষ অক্ষর (#) যেন সমস্যা না করে তাই নিরাপদে কানেক্ট করার কোড
$conn_string = "host='{$host}' port='{$port}' dbname='{$dbname}' user='{$user}' password='{$password}' sslmode='require'";
$conn = @pg_connect($conn_string);

if (!$conn) {
    // Port 5432 চেষ্টা করা হচ্ছে
    $conn_string = "host='{$host}' port='5432' dbname='{$dbname}' user='{$user}' password='{$password}' sslmode='require'";
    $conn = @pg_connect($conn_string);
}

if (!$conn) {
    die("Database Connection Failed. Please check database password.");
}
?>
