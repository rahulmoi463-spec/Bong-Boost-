<?php
// Error Reporting On
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/db.php';

// Security Token Check (For Cron Job & Security)
$secret_token = "bongboost2026"; // আপনার গোপন সিকিউরিটি কোড
$passed_token = $_GET['secret'] ?? '';

// এডমিন লগইন না থাকলে এবং সিক্রেট কোড ভুল হলে অ্যাক্সেস বন্ধ
if (!isset($_SESSION['admin_logged_in']) && $passed_token !== $secret_token) { 
    die("Unauthorized Access! Invalid Security Token."); 
}

$api_url = "https://fampage.in/api/v2";
$api_key = "KXKRSPSNsf8cQQKYHDRJjiNq6htJA9Uqm3Dii5GUfS9iIZzRZlhY3AX51dpd";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['key' => $api_key, 'action' => 'services']));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    die("cURL Error: " . curl_error($ch));
}
curl_close($ch);

$services = json_decode($response, true);

if (is_array($services)) {
    $count = 0;
    foreach ($services as $service) {
        $p_id = (int)($service['service'] ?? 0);
        if ($p_id <= 0) continue;

        $name = trim($service['name'] ?? '');
        $category = trim($service['category'] ?? '');
        $orig_rate = (float)($service['rate'] ?? 0);
        $selling_rate = $orig_rate * 1.30; // 30% Profit
        $min = (int)($service['min'] ?? 0);
        $max = (int)($service['max'] ?? 0);
        $cancel = (!empty($service['cancel'])) ? 1 : 0;
        $dripfeed = (!empty($service['dripfeed'])) ? 1 : 0;
        
        // Clean Description
        $raw_desc = $service['description'] ?? '';
        $clean_desc = preg_replace('/https?:\/\/(www\.)?(youtube\.com|youtu\.be)\/[^\s]+/i', '', $raw_desc);
        $clean_desc = preg_replace('/https?:\/\/[^\s]+/i', '', $clean_desc);
        $clean_desc = str_ireplace('fampage.in', 'bongboost.site.je', $clean_desc);
        $clean_desc = str_ireplace('fampage', 'Bong Boost', $clean_desc);
        $description = trim($clean_desc);

        // Database Check
        $check_query = "SELECT id FROM services WHERE provider_service_id = $1";
        $check_res = pg_query_params($dbconn, $check_query, array($p_id));

        if ($check_res && pg_num_rows($check_res) > 0) {
            // Update
            $update_query = "UPDATE services SET 
                name = $1, 
                category = $2, 
                original_rate = $3, 
                selling_rate = $4, 
                min_limit = $5, 
                max_limit = $6, 
                description = $7, 
                has_cancel = $8, 
                has_dripfeed = $9 
                WHERE provider_service_id = $10";
            
            pg_query_params($dbconn, $update_query, array(
                $name, $category, $orig_rate, $selling_rate, $min, $max, $description, $cancel, $dripfeed, $p_id
            ));
        } else {
            // Insert
            $insert_query = "INSERT INTO services 
                (provider_service_id, name, category, original_rate, selling_rate, min_limit, max_limit, description, has_cancel, has_dripfeed) 
                VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10)";
            
            pg_query_params($dbconn, $insert_query, array(
                $p_id, $name, $category, $orig_rate, $selling_rate, $min, $max, $description, $cancel, $dripfeed
            ));
        }
        $count++;
    }
    
    echo "Sync Completed Successfully! Total $count Services synced.";
} else {
    echo "Sync Failed! Invalid API Response.";
}
?>
