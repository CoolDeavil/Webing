<?php
use API\Core\Container\MicroDI;
use API\Core\Utils\CaptchaGen;
use API\Core\Utils\Logger;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
require_once(realpath('../') .
    DIRECTORY_SEPARATOR . 'Micro' .
    DIRECTORY_SEPARATOR . 'Config' .
    DIRECTORY_SEPARATOR . 'includes.php');
/**@var $bootstrap */
$ioc = MicroDI::getInstance($bootstrap);
$cp = new \API\Core\Utils\CaptchaGen();
//$human = $cp->gdCaptcha();
$human = $cp->imagickCaptcha();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Captcha</title>
    <link rel="stylesheet" href="css/main.min.css">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-6">
            <h2><?=$human->text ?></h2>
        </div>
        <div class="col-6">
            <img src="<?=$human->image ?>" alt="" class="img-fluid">
        </div>
        <div class="col-12">
            <code style="height: 200px;word-wrap: break-word;">
                <?= json_encode($human)?>
            </code>
        </div>

    </div>
</div>
</body>
</html>
