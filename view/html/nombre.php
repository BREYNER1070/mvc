
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= SITE_URL ?>view/img/styles.css">
    <title>Document</title>
</head>
<body>
  <?php

  require_once 'fpdf/fpdf.php';

  $pdf = new FPDF();

  echo "FPDF funcionando";
  ?>
</body>
</html>
