<?php
require './ManagePushToken.php';


$title = 'oii';
$body = 'descricao da not';

$fcmToken = "cX_5lMtvH46jjqvcndOjOx:APA91bFB4sFf6njCMKsecBkh68fz0dizeDM3jcyJtajsk6ItmA_jOZgh8b9q2YsUULZXRqIXRGJ9I5LnUfNiHA_obk4xOwqbFGWD56Z56CdRrgZjbtbYdgY";


sendNotification($title, $body, $fcmToken);
