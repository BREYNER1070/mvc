<?php
require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = $_ENV['MAIL_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['MAIL_USER'];
    $mail->Password   = $_ENV['MAIL_PASS'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = $_ENV['MAIL_PORT'];
    $mail->setFrom($_ENV['MAIL_USER'], 'Grand Eclat Hotel');
    $mail->addAddress($correo, $nombre); // ← vienen del controller
    $mail->isHTML(true);
    $mail->Subject = 'Bienvenido a Grand Eclat Hotel!';
    $mail->Body = '
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#f4f0e8;font-family:Georgia,serif;">

  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f0e8;padding:40px 0;">
    <tr><td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #d4c9b0;">

        <!-- ENCABEZADO DORADO -->
        <tr>
          <td style="background:#8b6914;padding:35px 40px;text-align:center;">
            <div style="font-size:28px;letter-spacing:4px;color:#ffffff;text-transform:uppercase;">
              Grand <em>Éclat</em>
            </div>
            <div style="font-size:11px;letter-spacing:3px;color:#f0e0b0;margin-top:6px;text-transform:uppercase;">
              Hotel · Cartagena de Indias, Colombia
            </div>
          </td>
        </tr>

        <!-- IMAGEN DECORATIVA -->
        <tr>
          <td style="background:#2d6a4f;padding:18px 40px;text-align:center;">
            <div style="font-size:13px;letter-spacing:2px;color:#a8d5b8;text-transform:uppercase;">
              ✦ &nbsp; Bienvenido a una experiencia única &nbsp; ✦
            </div>
          </td>
        </tr>

        <!-- CUERPO -->
        <tr>
          <td style="padding:45px 50px;">

            <p style="font-size:22px;color:#8b6914;margin:0 0 6px;">
              Hola, <strong>' . $nombre . '</strong>
            </p>
            <p style="font-size:13px;color:#888;margin:0 0 30px;letter-spacing:1px;text-transform:uppercase;">
              Tu cuenta ha sido creada exitosamente
            </p>

            <p style="font-size:15px;color:#444;line-height:1.8;margin:0 0 24px;">
              Es un placer darte la bienvenida a <strong style="color:#8b6914;">Grand Éclat Hotel</strong>. 
              A partir de ahora podés explorar nuestras habitaciones, hacer reservas y vivir 
              una experiencia de hospitalidad única en el corazón de Cartagena.
            </p>

            <!-- TARJETA DESTACADA -->
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#faf7f0;border:1px solid #d4c9b0;margin:28px 0;">
              <tr>
                <td style="padding:24px 28px;">
                  <div style="font-size:10px;letter-spacing:2px;color:#8b6914;text-transform:uppercase;margin-bottom:12px;">
                    Lo que te espera
                  </div>
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="padding:8px 0;border-bottom:1px solid #e8dfc8;font-size:14px;color:#333;">
                        🛏 &nbsp; Habitaciones de lujo con vista al mar
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:8px 0;border-bottom:1px solid #e8dfc8;font-size:14px;color:#333;">
                        🍽 &nbsp; Gastronomía gourmet colombiana e internacional
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:8px 0;border-bottom:1px solid #e8dfc8;font-size:14px;color:#333;">
                        💆 &nbsp; Spa y bienestar de primer nivel
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:8px 0;font-size:14px;color:#333;">
                        🌅 &nbsp; Atardeceres únicos en Cartagena de Indias
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            <!-- BOTÓN -->
            <table cellpadding="0" cellspacing="0" style="margin:10px auto;">
              <tr>
                <td style="background:#8b6914;padding:14px 40px;text-align:center;">
                  <a href="' . $_ENV['SITE_URL'] . '" 
                     style="color:#ffffff;font-family:Arial,sans-serif;font-size:13px;
                            letter-spacing:2px;text-decoration:none;text-transform:uppercase;">
                    Explorar el Hotel
                  </a>
                </td>
              </tr>
            </table>

          </td>
        </tr>

        <!-- PIE -->
        <tr>
          <td style="background:#1a1a1a;padding:28px 40px;text-align:center;">
            <div style="font-size:14px;letter-spacing:3px;color:#8b6914;text-transform:uppercase;margin-bottom:8px;">
              Grand Éclat
            </div>
            <div style="font-size:11px;color:#666;line-height:1.8;">
              Cartagena de Indias, Colombia<br>
              © 2026 Grand Éclat Hotel. Todos los derechos reservados.
            </div>
          </td>
        </tr>

      </table>
    </td></tr>
  </table>

</body>
</html>';
    $mail->send();
    return true;

} catch (Exception $e) {
    error_log('Error email: ' . $mail->ErrorInfo);
    return false;
}
?>  