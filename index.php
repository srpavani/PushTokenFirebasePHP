<?php
// Desativa o cache do navegador
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firebase Messaging</title>
</head>
<body>


  <?php echo rand(2, 99999)?>
  <p>Data e hora atual: <?php echo date('Y-m-d H:i:s'); ?></p>
    <h1>Firebase pushNotification</h1>
    <button id="subscribe">Generate Token</button>
    <p id="token" style="display: none;"></p>
    <p id="error" style="display: none; color: red;"></p>

    <script type="module" src="app.js"></script>
</body>
</html>
