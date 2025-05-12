<?php
header('Content-Type: application/json');

// Load API key securely
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from = strtoupper(trim($_POST['from'] ?? ''));
    $to = strtoupper(trim($_POST['to'] ?? ''));
    $amount = floatval($_POST['amount'] ?? 0);

    if ($from && $to && $amount > 0 && defined('EXCHANGE_API_KEY')) {
        $url = "https://api.exchangerate.host/convert?from={$from}&to={$to}&amount={$amount}&access_key=" . urlencode(EXCHANGE_API_KEY);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $apiResponse = curl_exec($ch);
        curl_close($ch);

        if ($apiResponse) {
            $data = json_decode($apiResponse, true);
            if (isset($data['result'])) {
                $converted = number_format($data['result'], 2);
                echo json_encode([
                    'success' => true,
                    'converted' => "{$amount} {$from} = {$converted} {$to}"
                ]);
                exit;
            }
        }
    }

    // Error fallback
    echo json_encode([
        'success' => false,
        'message' => 'Conversion failed. Please check the currency codes and amount.'
    ]);
    exit;
}
