<?php
$project_id = "xqekzayywscxlnqdhkcf";
$password   = "Bongboostdb#96";

// Render (IPv4) থেকে Supabase-এ কানেক্ট করার জন্য Pooler Host
$host = "aws-0-ap-northeast-2.pooler.supabase.com";
$port = "6543";
$dbname = "postgres";
$user = "postgres." . $project_id;

// pg_connect-এ পাসওয়ার্ড ও প্যারামিটার নিরাপদ রাখতে সিঙ্গেল কোট ব্যবহার করা হয়েছে
$conn_string = "host='{$host}' port='{$port}' dbname='{$dbname}' user='{$user}' password='{$password}' sslmode='require'";
$conn = @pg_connect($conn_string);

if (!$conn) {
    // যদি কোনো কারণে পোর্ট ৬৫৪৩ মিস হয়, পোর্ট ৫৪৩২ দিয়ে Pooler ট্রাই করবে
    $conn_string_alt = "host='{$host}' port='5432' dbname='{$dbname}' user='{$user}' password='{$password}' sslmode='require'";
    $conn = @pg_connect($conn_string_alt);
}

if (!$conn) {
    $err = error_get_last();
    echo "<b>Database Connection Error Details:</b><br>";
    echo $err['message'] ?? 'Unable to connect to database.';
    exit;
}
?>
