<?php
$password = "Bongboostdb#96";

// ১. Direct Host ট্রাই করা হচ্ছে
$conn_string = "host=db.xqekzayywscxnqdhkcf.supabase.co port=5432 dbname=postgres user=postgres password={$password} sslmode=require";
$conn = @pg_connect($conn_string);

// ২. যদি Direct ব্যর্থ হয় তবে Pooler ট্রাই করা হচ্ছে
if (!$conn) {
    $conn_string = "host=aws-0-ap-northeast-2.pooler.supabase.com port=6543 dbname=postgres user=postgres.xqekzayywscxnqdhkcf password={$password} sslmode=require";
    $conn = @pg_connect($conn_string);
}

if (!$conn) {
    // আসল এরর মেসেজটি প্রিন্ট করবে
    $error = error_get_last();
    echo "<b>Database Connection Failed Error:</b><br>" . ($error['message'] ?? 'Unknown Error');
    exit;
}
?>
