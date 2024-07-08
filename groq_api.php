<?php
include 'config.php';

function callGroqAPI($messages, $temperature = 0.8) {  // Default temperature is set to 1.0
    $data = [
        'model' => 'llama3-8b-8192',
        'messages' => $messages,
        'temperature' => $temperature,  // Add the temperature parameter here
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, GROQ_API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . GROQ_API_KEY,
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);

    $result = json_decode($response, true);
    return $result['choices'][0]['message']['content'];
}
?>
