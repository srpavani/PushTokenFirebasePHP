<?php


 // Envia uma notificação para dispositivos usando Firebase Cloud Messaging (V1).

function sendNotification($title, $body, $fcmToken)
{
    
    $serviceAccountJson = '{
        "Your json here"
    }';

    $serviceAccount = json_decode($serviceAccountJson, true);

    
    $fcmUrl = 'https://fcm.googleapis.com/v1/projects/' . $serviceAccount['project_id'] . '/messages:send';

    
    $accessToken = getAccessToken($serviceAccount);

    
    $payload = [
        'message' => [
            'token' => $fcmToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
        ],
    ];

    
    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json',
    ];

    
    $ch = curl_init();

    
    curl_setopt($ch, CURLOPT_URL, $fcmUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException("Erro ao enviar notificação: {$error}");
    }

    
    curl_close($ch);

    return [
        'httpCode' => $httpCode,
        'response' => json_decode($response, true),
    ];
}


function getAccessToken($serviceAccount)
{
    
    $header = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));

    
    $now = time();
    $claims = base64_encode(json_encode([
        'iss' => $serviceAccount['client_email'],
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        'aud' => $serviceAccount['token_uri'],
        'iat' => $now,
        'exp' => $now + 3600,
    ]));

    $unsignedJwt = "{$header}.{$claims}";

    
    $privateKey = $serviceAccount['private_key'];
    $signature = '';
    openssl_sign($unsignedJwt, $signature, $privateKey, 'sha256WithRSAEncryption');
    $signedJwt = "{$unsignedJwt}." . base64_encode($signature);

    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $serviceAccount['token_uri']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $signedJwt,
    ]));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException("Erro ao obter token de acesso: {$error}");
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        throw new RuntimeException("Erro ao obter token de acesso. Código HTTP: {$httpCode}. Resposta: {$response}");
    }

    $tokenResponse = json_decode($response, true);
    return $tokenResponse['access_token'];
}



