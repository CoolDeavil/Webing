<?php
/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 5/5/2017
 * Time: 3:09 PM
 */

namespace API\Core\Utils;

use Imagick;
use ImagickDraw;
use ImagickException;
use ImagickPixel;
use API\Core\Session\Session;
use API\Core\Utils\Logger;

class CaptchaGen
{
    /**
     * @throws \ImagickDrawException
     * @throws ImagickException
     */
    public static function moreHumanTest(): object
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        $captcha_text = substr(str_shuffle($letters), 0, CAPTCHA_LENGTH);

        $draw = new ImagickDraw();
        $draw->setFont("./fonts/AnonymousClippings.ttf");
        $draw->setFontSize(50);
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);
        $draw->setFillColor(new ImagickPixel('#006d6d'));
        $draw->setFillColor(new ImagickPixel('#000000'));
        $draw->setTextAlignment(Imagick::ALIGN_CENTER);
        $draw->annotation(150, 60, $captcha_text);

        $draw->setStrokeColor(new ImagickPixel("#ff1116;"));
        $draw->setStrokeWidth(2);
        $draw->line(40, 35, 250, 30);
//        $draw->line(40, 65, 250, 60);

        $imagick = new Imagick();
        $imagick->newImage(300, 150, "transparent");
        $imagick->setImageFormat("png");
        $imagick->drawImage($draw);
        $distort = [180];
        $imagick->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
        $imagick->setImageMatte(true);
        $imagick->distortImage(Imagick::DISTORTION_ARC, $distort, true);
        $imagick->writeImage("microCaptcha.png");
        $captcha = (object)[
            'image' => 'data:image/jpg;base64,' . base64_encode($imagick->getImageBlob()),
            'text' => $captcha_text
        ];
        Session::set('captcha', serialize($captcha));
        Logger::log('Captcha:  ' . $captcha->text);
        return $captcha;
    }
}

//class CaptchaGen
//{
//
//    public static function moreHumanTest()
//    {
//        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
//        $captcha_text = substr(str_shuffle($letters), 0, CAPTCHA_LENGTH);
//
//        $draw = new ImagickDraw();
//        $draw->setFont("./fonts/AnonymousClippings.ttf");
//        $draw->setFontSize(75);
//        $draw->setStrokeAntialias(true);
//        $draw->setStrokeWidth(1);
//        $draw->setTextAntialias(true);
//        $draw->setTextAlignment(Imagick::ALIGN_CENTER);
//        $draw->annotation(500, 500, "Lorem Ipsum!");
//
//        $draw = new ImagickDraw();
//        $draw->setFont("./fonts/AnonymousClippings.ttf");
//        $draw->setFontSize(75);
//        $draw->setStrokeAntialias(true);
//        $draw->setTextAntialias(true);
//        $draw->setFillColor('#006d6d');
//        $draw->setFillColor(new ImagickPixel('#000000'));
//        $draw->setTextAlignment(Imagick::ALIGN_CENTER);
//        $draw->annotation(250, 80, $captcha_text);
//
////        $draw->setStrokeColor(new ImagickPixel("#ff1116;"));
////        $draw->setStrokeWidth(2);
////        $draw->line(60, 35, 450, 30);
////        $draw->line(60, 65, 450, 60);
//
//        $imagick = new Imagick();
//        $imagick->newImage(500, 500, "transparent");
//        $imagick->setImageFormat("png");
//        $imagick->drawImage($draw);
//        $distort = [180];
//        $imagick->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
//        $imagick->setImageMatte(true);
//        $imagick->distortImage(Imagick::DISTORTION_ARC, $distort, true);
////        $imagick->writeImage("microCaptcha.png");
//        $captcha = (object)[
//            'image' => 'data:image/jpg;base64,' . base64_encode($imagick->getImageBlob()),
//            'text' => $captcha_text
//        ];
//        Session::set('captcha', serialize($captcha));
//        Logger::log('Captcha:  ' . $captcha->text);
//        return $captcha;
//    }
//    public static function humanTest()
//    {
//        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
////        $string = substr(str_shuffle($letters), 2, 6);
//        $len = strlen($letters);
//        $captcha_text = '';
//        for ($i = 0; $i< CAPTCHA_LENGTH; $i++) {
//            $captcha_text.=$letters[rand(0, $len-1)];
//        }
//
//        $draw = new ImagickDraw();
//        $draw->setFont("./fonts/AnonymousClippings.ttf");
//        $draw->setFontSize(75);
//        $draw->setStrokeAntialias(true);
//        $draw->setTextAntialias(true);
////        $draw->setFillColor('#006d6d');
//        $draw->setFillColor(new ImagickPixel('#000000'));
//        $draw->setTextAlignment(Imagick::ALIGN_CENTER);
//        $textOnly=null;
//        try {
//            $textOnly = new Imagick();
//        } catch (ImagickException $e) {
//        }
//
//        $textOnly->newImage(400, 200, "transparent");
//        $textOnly->setImageFormat('png');
//        $textOnly->annotateImage($draw, 30, 80, 0, $captcha_text);
////        $textOnly->setTextAlignment(\Imagick::ALIGN_CENTER);
//
////        $textOnly->trimImage(10);
////        $textOnly->setImagePage($textOnly->getimageWidth(), $textOnly->getimageheight(), 0, 0);
//
////        $distort = [120];
////        $textOnly->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
////        $textOnly->setImageMatte(true);
////        $textOnly->distortImage(Imagick::DISTORTION_ARC, $distort, true);
//
////        $textOnly->swirlImage(20);
//
////        $draw->setStrokeColor(new ImagickPixel("#23ff04;"));
////
////        $draw->line(0, 30, 600, 30);
////        $draw->line(0, 120, 600, 10);
////        $draw->line(30, 0, 600, 600);
////        $draw->line(90, 0, 600, 600);
////
////        $textOnly->drawImage($draw);
//        $textOnly->setformat('png');
//
//        $textOnly->writeImage("microCaptcha.png");
//        //header("Content-Type: image/png");
//        //echo $textOnly->getimageblob();
//        //ob_start();
//        //echo $im->getImageBlob();
//        //$contents =  ob_get_contents();
//        //ob_end_clean();
//
//        $captcha = (object)[
//            'image' => 'data:image/jpg;base64,' . base64_encode($textOnly->getImageBlob()),
//            'text' => $captcha_text
//        ];
//        Session::set('captcha', serialize($captcha));
//        Logger::log('Captcha:  ' . $captcha->text);
//        return $captcha;
//    }
//    public static function humanTest__()
//    {
//        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
//        $len = strlen($letters);
//        $captcha_text = '';
//        for ($i = 0; $i< CAPTCHA_LENGTH; $i++) {
//            $letter = $letters[rand(0, $len-1)];
//            $captcha_text.=$letter;
//        }
//
//        $draw = new ImagickDraw();
//        $draw->setFont("./fonts/HandelGothic.ttf");
//        $draw->setFontSize(35);
//        $draw->setStrokeAntialias(true);
//        $draw->setTextAntialias(true);
//        $draw->setFillColor('#fa6a1f');
//
//        $textOnly=null;
//        try {
//            $textOnly = new Imagick();
//        } catch (\ImagickException $e) {
//        }
//
//        $textOnly->newImage(600, 300, "transparent");
//        $textOnly->setImageFormat('png');
//        $textOnly->annotateImage($draw, 30, 40, 0, $captcha_text);
//        $textOnly->trimImage(0);
//        $textOnly->setImagePage($textOnly->getimageWidth(), $textOnly->getimageheight(), 0, 0);
//
//        $distort = [110];
//        $textOnly->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
//        $textOnly->setImageMatte(true);
//        $textOnly->distortImage(Imagick::DISTORTION_ARC, $distort, false);
//
//        $textOnly->swirlImage(40);
//
//        $draw->setStrokeColor(new ImagickPixel("#000000"));
//
//        $draw->line(0, 10, 600, 30);
//        $draw->line(10, 30, 600, 10);
//        $draw->line(50, 50, 600, 50);
//
//        $textOnly->drawImage($draw);
//        $textOnly->setformat('png');
//
//        //$textOnly->writeImage("microCaptcha.png");
//
//        //header("Content-Type: image/png");
//        //echo $textOnly->getimageblob();
//
//        //ob_start();
//        //echo $im->getImageBlob();
//        //$contents =  ob_get_contents();
//        //ob_end_clean();
//
//        $captcha = (object)[
//            'image' => 'data:image/jpg;base64,' . base64_encode($textOnly->getImageBlob()),
//            'text' => $captcha_text
//        ];
//        Session::set('captcha', serialize($captcha));
//        Logger::log('captcha ' . $captcha->text);
//        return $captcha;
//    }
//    public static function humanSecureCaptcha()
//    {
//        define('NUM_CHAR', 6);
//        # Make random text Captcha
//        #$letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
//        $letters = 'ABCDEFGHJKMNOPQRSTUVWXYZabcdefghjkmnopqrstuvwxyz';
//
//        $len = strlen($letters);
//        $captcha_text = '';
//        for ($i = 0; $i< NUM_CHAR; $i++) {
//            $letter = $letters[rand(0, $len-1)];
//            $captcha_text.=$letter;
//        }
//        $draw = new ImagickDraw();
//        $draw->setFont('./fonts/Lato-Regular.ttf');
//        $draw->setFontSize(20);
//        $draw->setStrokeAntialias(true);
//        $draw->setTextAntialias(true);
//        $c = '#000000';
//        /** @var $c ImagickPixel */
//        $draw->setFillColor($c);
//        $textOnly = new Imagick();
//        $textOnly->newImage(500, 100, "transparent");
//        $textOnly->setImageFormat('png');
//        $textOnly->annotateImage($draw, 30, 40, 0, $captcha_text);
//        $textOnly->trimImage(0);
//        $textOnly->setImagePage($textOnly->getimageWidth(), $textOnly->getimageheight(), 0, 0);
//        $distort = [ 180 ];
//        $textOnly->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
//
//        $textOnly->setImageMatte(true);
//        $textOnly->distortImage(Imagick::DISTORTION_ARC, $distort, false);
//        #$textOnly->swirlImage(30);
//        /*
//        $draw = new \ImagickDraw();
//        $draw->setStrokeColor('#000000');
//        $draw->setFillColor('#000000');
//        $draw->setStrokeWidth(0.5);
//        $draw->line(1, 1, 150, 75);
//        $textOnly->drawImage( $draw );
//         */
//        $textOnly->setformat('png');
//
//        ob_start();
//        echo $textOnly->getImageBlob();
//        $contents =  ob_get_contents();
//        ob_end_clean();
//
//        $captcha = (object)[
//            'image' => 'data:image/jpg;base64,' . base64_encode($contents),
//            'text' => $captcha_text
//        ];
//        Session::set('captcha', serialize($captcha));
//        return $captcha;
//    }
//    public static function humanTestHard()
//    {
//        define('NUM_CHAR', 6);
//        $font = './fonts/Lato-Bold.ttf';
//
//        $im=null;
//        try {
//            $im = new Imagick();
//        } catch (\ImagickException $e) {
//        }
//        $im->newImage(150, 75, '#ffffff');
//        $draw = new ImagickDraw();
//
//        $c = '#000000';
//        /** @var $c ImagickPixel */
//        $draw->setFillColor($c);
//        $draw->setFont($font);
//        $draw->setFontSize(36);
//        $draw->setGravity(Imagick::GRAVITY_CENTER);
//        # Make random text Captcha
//        $letters = 'ABCDEFGHKMNOPQRSTUVWXYZabcdefghkmnopqrstuvwxyz';
//        $len = strlen($letters);
//        $captcha_text = '';
//        for ($i = 0; $i< NUM_CHAR; $i++) {
//            $letter = $letters[rand(0, $len-1)];
//            $captcha_text.=$letter;
//        }
//        $draw->annotation(0, 0, $captcha_text);
//        $im->drawImage($draw);
//        $im->setImageFormat("jpg");
//        $im->swirlImage(40);
//
//
//        $draw = new ImagickDraw();
//        $c = '#000000';
//        /** @var $c ImagickPixel */
//        $draw->setStrokeColor($c);
//        $draw->setFillColor($c);
//        $draw->setStrokeWidth(1);
//        $draw->line(1, 1, 150, 75);
//        $draw->line(1, 75, 150, 10);
//
//        #$draw->setStrokeWidth(2);
//        #$draw->line(1, 28, 150, 38);
//        $im->drawImage($draw);
//
//
//        # Fill new visible areas with transparent
//        //$im->setImageVirtualPixelMethod(\Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
//        //$im->setImageVirtualPixelMethod(\Imagick::VIRTUALPIXELMETHOD_BACKGROUND);
//        /* Activate matte */
//        //$imagick->setImageMatte(true);
//
//        # Control points for distortion
//        $points = [
//            0,0, 25,25, # top left
//            150,0, 126,0, # top right
//            0,135, 0,105, # bottom right
//            150,135, 150,135 # bottum left
//        ];
//        #  Perform the distortion
//        $im->distortImage(Imagick::DISTORTION_PERSPECTIVE, $points, true);
//
//        ob_start();
//        echo $im->getImageBlob();
//        $contents =  ob_get_contents();
//        ob_end_clean();
//
//        $captcha = (object)[
//            'image' => 'data:image/jpg;base64,' . base64_encode($contents),
//            'text' => $captcha_text
//        ];
//        #Session::set('captcha', serialize($captcha));
//        Logger::log("humanTest_hard");
//
//
//        return $captcha;
//    }
//    public function genCaptcha()
//    {
//        // Create the image
//        $im = imagecreatetruecolor(400, 30);
//        // Create some colors
//        $white = imagecolorallocate($im, 255, 255, 255);
//        #$grey = imagecolorallocate($im, 128, 128, 128);
//        $black = imagecolorallocate($im, 0, 0, 0);
//        imagefilledrectangle($im, 0, 0, 399, 29, $white);
//        // The text to draw
//        $text = 'CaptCha';
//        // Replace path by your own font path
//        //$font = '/var/www/html/reloaded/public/arial.ttf';
//        $font = './Lato-Bold.ttf';
//        // Add the text
//        imagettftext($im, 20, 4, 10, 20, $black, $font, $text);
//        ob_start(); // Let's start output buffering.
//            imagepng($im); //This will normally output the image, but because of ob_start(), it won't.
//            $contents = ob_get_contents(); //Instead, output above is saved to $contents
//        ob_end_clean(); //End the output buffer.
//        $dataUri = "data:image/jpeg;base64," . base64_encode($contents);
//        $foo =  (object)$captcha = [
//            'image' => $dataUri
//        ];
//
//        MicroLog::log("genCaptcha");
//        return  $foo;
//    }
//    public function generate()
//    {
//        define('NUM_CHAR', 6);
//        # Create image
//        $image = imagecreatetruecolor(150, 30);
//        $background_color = imagecolorallocate($image, 255, 255, 255);
//        imagefilledrectangle($image, 0, 0, 150, 30, $background_color);
//        # Make random text Captcha
//        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
//        $len = strlen($letters);
//
//        $text_color = imagecolorallocate($image, 0, 0, 0);
//        $word = '';
//        for ($i = 0; $i< NUM_CHAR; $i++) {
//            $letter = $letters[rand(0, $len-1)];
//            imagestring($image, 15, 5+($i*25), 10, $letter, $text_color);
//            $word.=$letter;
//        }
//        ob_start();                        // Let's start output buffering.
//            imagepng($image);              // This will normally output the image, but because of ob_start(), it won't.
//            $contents = ob_get_contents(); // Instead, output above is saved to $contents
//        ob_end_clean();                    // End the output buffer.
//
//        $dataUri = "data:image/jpeg;base64," . base64_encode($contents);
//        return (object)$captcha = [
//            'image' => $dataUri,
//            'captcha' => $word
//        ];
//    }
//}
