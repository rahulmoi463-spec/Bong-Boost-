<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';

if (!isset($_SESSION['admin_logged_in'])) { 
    header("Location: login.php"); 
    exit(); 
}

$api_url = "https://fampage.in/api/v2";
$api_key = "KXKRSPSNsf8cQQKYHDRJjiNq6htJA9Uqm3Dii5GUfS9iIZzRZlhY3AX51dpd";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['key' => $api_key, 'action' => 'services']));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 60); // 60 Seconds Timeout added
$response = curl_exec($ch);
curl_close($ch);

$services = json_decode($response, true);

if (is_array($services)) {
    $count = 0;
    foreach ($services as $service) {
        $p_id = (int)$service['service'];
        $name = trim($service['name'] ?? '');
        $category = trim($service['category'] ?? '');
        $orig_rate = (float)($service['rate'] ?? 0);
        $selling_rate = $orig_rate * 1.30; // 30% Profit Added
        $min = (int)($service['min'] ?? 0);
        $max = (int)($service['max'] ?? 0);
        $cancel = (!empty($service['cancel'])) ? 1 : 0;
        $dripfeed = (!empty($service['dripfeed'])) ? 1 : 0;
        
        // --- Smart Description Cleaning & Security Filter ---
        $raw_desc = isset($service['description']) ? $service['description'] : '';
        
        // 1. YouTube links removal
        $clean_desc = preg_replace('/https?:\/\/(www\.)?(youtube\.com|youtu\.be)\/[^\s]+/i', '', $raw_desc);
        
        // 2. Remove all external website links
        $clean_desc = preg_replace('/https?:\/\/[^\s]+/i', '', $clean_desc);
        
        // 3. Replace Fampage name with Bong Boost
        $clean_desc = str_ireplace('fampage.in', 'bongboost.site.je', $clean_desc);
        $clean_desc = str_ireplace('fampage', 'Bong Boost', $clean_desc);
        
        $description = trim($clean_desc);

        // Check if service already exists in database
        $check_query = "SELECT id FROM services WHERE provider_service_id = $1";
        $check_res = pg_query_params($dbconn, $check_query, array($p_id));

        if ($check_res && pg_num_rows($check_res) > 0) {
            // Update Existing Service
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
            
            $res = pg_query_params($dbconn, $update_query, array(
                $name, $category, $orig_rate, $selling_rate, $min, $max, $description, $cancel, $dripfeed, $p_id
            ));
        } else {
            // Insert New Service
            $insert_query = "INSERT INTO services 
                (provider_service_id, name, category, original_rate, selling_rate, min_limit, max_limit, description, has_cancel, has_dripfeed) 
                VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10)";
            
            $res = pg_query_params($dbconn, $insert_query, array(
                $p_id, $name, $category, $orig_rate, $selling_rate, $min, $max, $description, $cancel, $dripfeed
            ));
        }
        
        if ($res) {
            $count++;
        }
    }
    echo "<div style='background:#0f172a; color:#fff; padding:40px; font-family:sans-serif; text-align:center; height:100vh;'>";
    echo "<h2 style='color:#22c55e; font-size: 28px;'>Sync Completed Successfully!</h2>";
    echo "<p style='font-size:18px;'>Total <strong>$count</strong> Services synced cleanly with 30% Profit Margin!</p>";
    echo "<br><a href='dashboard.php' style='background:#6366f1; color:#fff; padding:12px 25px; text-decoration:none; border-radius:8px; font-weight:bold;'>Go to Admin Dashboard</a>";
    echo "</div>";
} else {
    echo "<div style='background:#0f172a; color:#fff; padding:40px; font-family:sans-serif; text-align:center; height:100vh;'>";
    echo "<h2 style='color:#ef4444;'>Sync Failed!</h2>";
    echo "<p>Could not connect to Provider API. Please check connection.</p>";
    echo "<a href='dashboard.php' style='color:#38bdf8;'>Back to Admin Panel</a>";
    echo "</div>";
}
?>
