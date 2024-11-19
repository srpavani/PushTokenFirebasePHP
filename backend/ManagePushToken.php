<?php


 // Envia uma notificação para dispositivos usando Firebase Cloud Messaging (V1).

function sendNotification($title, $body, $fcmToken)
{
    
    $serviceAccountJson = '{
        "type": "service_account",
        "project_id": "testzurich-34905",
        "private_key_id": "d16a0bb278702938740f38b223bcfb546c89e411",
        "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCvAMgW+NfRbc2P\nciV2OqFTA8agFbTJIfRF1U5JBkVJGLuH1y8LSz8Qc8McxuBD3Ju/jg99gPxzsX49\nU+iIXRh/fwWC9UVXXbnxDZxwex5KweG/9IRzBjgt6i/iVSRwci9ZbZFTlelTmiLI\n0u2j6z4ivwHG/4HNp+N1yVpA5j893154YPT6poTdaqLltan2TSU+Ng2LE9xdHj1C\nXeg2YOu2vq/uLzHqB4yfhijcdqbTa38ehuyMhHzVXjH3JRru5M/Gwy8SxQM8IMiV\n1Y/bIyxT3/A0CCNYkGbFWrezW7pI+fG5qFD5Kwi3FLhIQQUKG6rMGC3NmtQh4Mb0\nxMs635bPAgMBAAECggEAH8cgDk/UfMKYE6ayF8Rwu+7RGAuo/ubsAOxJYWhCl5Hm\np7WS5NWel4G12dlhKuF55LBKrssHzIhb+I3uiSiChNBPc2bPVxx9YcDLef9ZUlDf\niehATtS/ydKfLFWynrqVT4NeYtOzgUtZaqcmoN/cMhoGHBkUfIAyhRZxjZxhun2y\nUFN9DjijGDNiuQ0j8zHlT3mvBX5r726hDuXtKd/gm4Jjb4sSdn73eAdWmOAVLgPM\n4x80MG0xrQB6XMEUBUdLuaX1uPthE0kuXOVjCA1YrKAPlxrbu5HYmVvYHUbBjjab\nqGmYW7DTiPch8ct+HCEETw/gJfWBDG3wBwoPHFzy7QKBgQDrSZQQwxflRrNZrVTD\nZ/G/OdGb5fWmFI6VjmDKxXaBJpSNG15JOFbBU0csxhn6bSPE3TcLhEN/HZJxHxVL\nQTk/7c91ANhjHyYLfIyDc+zn1n/BiIE0FgtbcnaYC8YMg6WzB0fUFv6MW/8Hdh1E\ngc5KFgJisl39HL6sYvWrg6sd+wKBgQC+aKOJq3VBGiGheSy5VM5IGN8iz8Fj/Pp4\nvhE6JFgKT7y1Cj2jxObt2IGoU7LhYXkGEyVdfwMAvzQRA1r3m3Fk6UwGPoQRC8v9\nlBRbar4WaYwwy8526geVmLrlpU6mmOom54bhYUhOaUojX0o/00dQFL5PwEhsftuD\nmdeCngu2PQKBgQDdfZBTDzTpctrXKqJGqQ0cFNPLTHko9OUcyME5YRWjkqv5AbCK\ngOy6ZSj1by95XVB0vEJbJxFbEn2O29Hx296G3dmTrU4GUYZA/ehQvspfnL77cwMe\nAzqBxYj6rOqtUSilFc96SoMpoli7r2bx3LhTadCpFHwCEBM2uJnH8dUeDQKBgDjD\n1U1rsk8wTSpoh67Q25ae45brPQpkFv/8GgKtZxnb37RRU0MJppbt3umh2kha2Mu4\n1YDkJq8IUatoxqveyZV9/840wQab77qvdMo0LmtcGBFMEUdeSdiNadqgx6vfFp92\nkr2KVRYbSaD1/Zq9kBsNtBbuiijeQO8g5CDqoS85AoGAXVG/vpiSi2m/Ed8TWOy0\naZ6S5+WOQQLM+LyZOj36i8hvRsRKqtv/Ys1jRf09oxTgOouO4w81DLX593Nd1Wzk\nb4rxzj6X06eJBTnFRyFlGAG/vpe+GYtrF0bE5hlP24GGtjVCbjS1gCuAJD7GJeeK\nljt2ftAjgYEfkLRfOMYP7to=\n-----END PRIVATE KEY-----\n",
        "client_email": "firebase-adminsdk-ot4ea@testzurich-34905.iam.gserviceaccount.com",
        "token_uri": "https://oauth2.googleapis.com/token"
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



