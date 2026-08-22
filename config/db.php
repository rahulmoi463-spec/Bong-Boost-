<?php
$project_id = "xqekzayywscxlnqdhkcf";
$password   = "Bongboostdb#96";

// SSL mode disable/prefer এবং urlencode করে কানেকশন ট্রাই
$conn_string1 = "host=aws-0-ap-northeast-2.pooler.supabase.com port=6543 dbname=postgres user=postgres.{$project_id} password=" . urlencode($password);
$conn = @pg_connect($conn_string1);

if (!$conn) {
    // Direct host connection try
    $conn_string2 = "host=db.{$project_id}.supabase.co port=5432 dbname=postgres user=postgres password=" . urlencode($password);
    $conn = @pg_connect($conn_string2);
}

if (!$conn) {
    // Exact PostgreSQL error showing
    $err = error_get_last();
    echo "<b>Database Connection Error Details:</b><br>";
    echo $err['message'] ?? 'Unable to connect to database.';
    exit;
}
?>
