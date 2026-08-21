<?php
// Supabase PostgreSQL Connection
$host     = "db.xqekzayywscxlnqdhkcf.supabase.co";
$port     = "5432";
$dbname   = "postgres";
$user     = "postgres";
$password = "bongboostdb#96"; // এখানে Supabase তৈরির সময় দেওয়া পাসওয়ার্ডটি বসাবেন

$conn_string = "host={$host} port={$port} dbname={$dbname} user={$user} password={$password}";
$dbconn = pg_connect($conn_string);

if(!$dbconn) {
    die("Database Connection Failed");
}
?>
