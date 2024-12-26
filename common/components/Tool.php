<?php namespace common\components;

use common\components\HttpException;
use common\components\type;
use common\models\entities\Config;
use common\models\entities\EmailCheckCodes;
use common\models\entities\Performance;
use common\models\entities\PhoneCheckCodes;
use Exception;
use libphonenumber\PhoneNumberUtil;
use SteelyWing\Chinese\Chinese;
use Yii;
use yii\base\Component;
use yii\helpers\Url;

class Tool extends Component {
    protected $decimals = 4;

    public function toBaseUrl($route, $fullPath = false) {
        return str_replace("/#", "#", Url::to($route, $fullPath));
    }

    public function toCommonUrl($route) {
        return Yii::$app->params["staticFileUrl"] . Url::to($route);
    }

    /**
     *
     * @param type $paramAry ["keyy"=>"value"]
     * @return type
     */
    public function toCurrent($paramAry, $scheme = false) {
        $url = Url::current($paramAry, $scheme);
        $url = str_replace("/" . Yii::$app->defaultRoute, "", $url);
        $url = str_replace("/" . Yii::$app->controller->defaultAction, "", $url);
        return $url;
    }

    public function getAlternateUrl($lang) {
        if (isset(Yii::$app->params['alternateUrl'][$lang])) {
            return Yii::$app->params['alternateUrl'][$lang];
        } else {
            return $this->toCurrent(['language' => $lang], true);
        }
    }

    public function formatErrorMsg($errorsAry, $displayHtml = false) {
        if (!$errorsAry) {
            return null;
        }
        $result = "";

        foreach ($errorsAry as $key => $value) {
            for ($i = 0; $i < count($value); $i++) {
                $result .= $value[$i];
            }
            if ($displayHtml) {
                $result .= "<br/>";
            }
        }

        return $result;
    }

    public function sendMail($toEmail, $toBcc = null, $subject = null, $parameter = [], $viewName = "default") {
        $email = Config::getCacheByKey('email')->content;
        $response = Yii::$app->mailer->compose($viewName, $parameter)->setFrom([$email => $email])->setTo($toEmail)->setSubject($subject);

        if (($toEmail != $toBcc) && ($toBcc != null)) {
            $response->setBcc($toBcc);
        }

        $result = $response->send();

        return $result;
    }

    public function sendUserEmailCheckCode($user, $type = "regist", $other = null) {
        $emailModel = new \common\models\entities\EmailCheckCodes;
        $checkCode = $this->generatorRandomString();

        $emailModel->deleteAll("member_id = '{$user->id}'");
        $emailModel->attributes = [
            "member_id"  => $user->id,
            "check_code" => $checkCode,
            'email'      => $user->email,
            'type'       => $type,
            'other'      => $other,
        ];

        if (!$emailModel->save()) {
            throw new \yii\web\HttpException(400, $this->formatErrorMsg($emailModel->getErrors()));
        }

        if ($type == EmailCheckCodes::TYPE_REGIST) {
            $subject = Yii::t('app', 'mailCheckCodeTitle')["register"];
            $view = "emailcheck_" . Yii::$app->language;
            $url = $this->toBaseUrl([
                "user/regist-email-confirm",
                "checkCode" => $checkCode,
                "language"  => Yii::$app->language
            ], true);
        } elseif ($type == EmailCheckCodes::TYPE_FORGET_PASSWORD) {
            $subject = Yii::t('app', 'mailCheckCodeTitle')["forgetPwd"];
            $view = "emailforgetpwd_" . Yii::$app->language;
            $url = $this->toBaseUrl([
                "user/forget-pwd-confirm",
                "checkCode" => $checkCode,
                "language"  => Yii::$app->language
            ], true);
        } else {
            throw new \yii\web\HttpException(400, "認證碼類型有誤");
        }

        return $this->sendMail($user->email, $user->email, $subject, [
            "user"  => $user,
            "url"   => $url,
            "other" => $other,
        ], $view);
    }

    public function mask_email($email, $mask_char = "*", $percent = 50) {
        list($user, $domain) = preg_split("/@/", $email);
        $len = strlen($user);
        $mask_count = floor($len * $percent / 100);
        $offset = floor(($len - $mask_count) / 2);
        $masked = substr($user, 0, $offset) . str_repeat($mask_char, $mask_count) . substr($user, $mask_count + $offset);

        return ($masked . '@' . $domain);
    }

    public function generatorRandomString($length = 7, $prefix = "", $numOnly = false) {
        $password_len = $length;
        $password = $prefix;

        if ($numOnly) {
            $word = '1234567890';
        } else {
            // remove o,0,1,l
            $word = 'abcdefghjkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789';
        }

        $len = strlen($word);

        for ($i = 0; $i < $password_len; $i++) {
            $password .= $word[rand() % $len];
        }

        return $password;
    }

    public function generatorHashString($input, $length = 13, $prefix = null) {
        $result = md5($input . uniqid($prefix, true));
        $result = substr($result, 0, $length);
        $result = $prefix . strtolower($result);

        return $result;
    }

    public function convertImageToJpg($originalImage, $outputImage) {
        try {
            $ext = exif_imagetype($originalImage);

            if ($ext == IMAGETYPE_JPEG || $ext == IMAGETYPE_JPEG2000) {
                $imageTmp = imagecreatefromjpeg($originalImage);
            } elseif ($ext == IMAGETYPE_PNG) {
                $imageTmp = imagecreatefrompng($originalImage);
            } elseif ($ext == IMAGETYPE_GIF) {
                $imageTmp = imagecreatefromgif($originalImage);
            } elseif ($ext == IMAGETYPE_BMP) {
                $imageTmp = imagecreatefrombmp($originalImage);
            } else {
                return false;
            }

            // quality is a value from 0 (worst) to 100 (best)
            imagejpeg($imageTmp, $outputImage, 90);
            imagedestroy($imageTmp);

            return $outputImage;
        } catch (\Exception $ex) {
            return file_put_contents($outputImage, file_get_contents($originalImage));
        }
    }

    public function thumbnailByWithRatio($source, $target, $type = "member") {
        //縮圖
        $target = $this->convertImageToJpg($source, $target);
        $imagine = new \Imagine\Gd\Imagine();
        $_thumbWidth = Yii::$app->params["thumbWidth"][$type]["width"];
        $_thumbHeight = Yii::$app->params["thumbWidth"][$type]["height"];
        $_image = $imagine->open($source);
        $_width = $_image->getSize()->getWidth();
        $_height = $_image->getSize()->getHeight();
        $_margin = $_marginWidth = $_marginHeight = 0;

        if ($_width < $_thumbWidth) {
            //寬度不夠加padding
            $_marginWidth = ($_thumbWidth - $_width) / 2;

            if ($_height < $_thumbHeight) {
                //高度不夠加padding
                $_marginHeight = ($_thumbHeight - $_height) / 2;
            }
            $_margin = $_marginWidth > $_marginHeight ? $_marginWidth : $_marginHeight;

            \yii\imagine\Image::frame($target, (int)$_margin, "FFF")->save($target);

            $newWidth = $_width;
            $newHeight = $_height;
        } else {
            //寬度太長先縮寬度
            $newWidth = $_thumbWidth;
            $newHeight = (int)(($_thumbWidth / $_width) * $_height);
            \yii\imagine\Image::resize($target, $newWidth, $newHeight)->save($target);

            if ($newHeight < $_thumbHeight) {
                //高度不夠加padding 再縮圖
                $_margin = ($_thumbHeight - $newHeight) / 2;
                \yii\imagine\Image::frame($target, (int)$_margin, "FFF")->save($target);
            }
        }

        $nowWidth = $newWidth + $_margin * 2;
        $nowHeight = $newHeight + $_margin * 2;
        $_start = [
            ($nowWidth - $_thumbWidth) / 2,
            ($nowHeight - $_thumbHeight) / 2
        ];

        \yii\imagine\Image::crop($target, $_thumbWidth, $_thumbHeight, $_start)->save($target);
    }

    public function isJson($string) {
        $array = @json_decode($string, true);
        return !empty($string) && is_string($string) && is_array($array) && !empty($array) && json_last_error() == 0;
    }

    public function toProxyImg($url) {
        return Yii::$app->params["staticFileUrl"] . "/img/" . bin2hex($url) . ".webp";
    }

    public function getChineseWeekday($unixtime) {
        $weekday = date('w', $unixtime);
        return '星期' . [
                            '日',
                            '一',
                            '二',
                            '三',
                            '四',
                            '五',
                            '六'
                        ][$weekday];
    }

    public function webpImage($source, $quality = 100, $removeOld = true) {
        $dir = pathinfo($source, PATHINFO_DIRNAME);
        $name = pathinfo($source, PATHINFO_FILENAME);
        $destination = $dir . DIRECTORY_SEPARATOR . $name . '.webp';
        $info = getimagesize($source);

        if (!$info) {
            return false;
        }

        $isAlpha = false;

        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source);
        } elseif ($isAlpha = $info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source);
        } elseif ($isAlpha = $info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source);
        } else {
            return false;
        }
        if ($isAlpha) {
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
        }
        imagewebp($image, $destination, $quality);

        if ($removeOld) {
            unlink($source);
        }

        return true;
    }

    public function pngImage($source, $quality = 100, $removeOld = true) {
        $dir = pathinfo($source, PATHINFO_DIRNAME);
        $name = pathinfo($source, PATHINFO_FILENAME);
        $destination = $dir . DIRECTORY_SEPARATOR . $name . '.png';
        $info = getimagesize($source);

        if (!$info) {
            return false;
        }

        $isAlpha = false;
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source);
        } elseif ($isAlpha = $info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source);
        } elseif ($isAlpha = $info['mime'] == 'image/webp') {
            $image = imagecreatefromwebp($source);
        }elseif ($isAlpha = $info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source);
        } else {
            return false;
        }
        if ($isAlpha) {
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
        }

        imagepng($image, $destination, $quality);

        if ($removeOld) {
            unlink($source);
        }

        return true;
    }

    public function genTransactionId($prefix, $length = 3) {
        $TransactionCode = "";
        for ($i = 0; $i <= $length; $i++) {
            $TransactionCode .= mt_rand(0, 9);
        }

        $TransactionCode = $prefix . date("YmdHis") . $TransactionCode;
        return $TransactionCode;
    }

    public function twTocn($string) {
        $chinese = new Chinese();
        return $chinese->to(Chinese::CHS, $string);
    }

    public function cnTotw($string) {
        $chinese = new Chinese();
        return $chinese->to(Chinese::CHT, $string);
    }

    public function safeEncrypt(string $message, $keyname = "encryptKey"): string {
        $key = $this->getEncryptKey($keyname);
        if (mb_strlen($key, '8bit') !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
            throw new Exception('Key is not the correct size (must be 32 bytes).');
        }
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        $cipher = base64_encode($nonce . sodium_crypto_secretbox($message, $nonce, $key));
        sodium_memzero($message);
        sodium_memzero($key);
        return base64_encode($cipher);
    }

    /*
     * 字串加密
     */

    public function getEncryptKey($keyname = "encryptKey") {
        return base64_decode(Yii::$app->params[$keyname]);
    }

    /*
     * 字串解密
     */

    public function safeDecrypt(string $encrypted, $keyname = "encryptKey"): string {
        try {
            $encrypted = base64_decode($encrypted);
            $key = $this->getEncryptKey($keyname);
            $decoded = base64_decode($encrypted);
            $nonce = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
            $ciphertext = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

            $plain = sodium_crypto_secretbox_open($ciphertext, $nonce, $key);
            if (!is_string($plain)) {
                throw new Exception('Invalid MAC');
            }
            sodium_memzero($ciphertext);
            sodium_memzero($key);

            return $plain;
        } catch (Exception $ex) {
            throw new Exception('加密參數不合法');
        }
    }

    /**
     * 格式化電話號碼
     * @param $phone
     * @return string
     * @throws HttpException
     */
    public function formatPhone($phone, $onlyTw = false) {
        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        try {
            if (strpos($phone, "09") === 0) {
                $phone = substr($phone, 2);
                $phone = "+8869" . $phone;
            }
            $phoneNumberObject = $phoneNumberUtil->parse($phone);
        } catch (Exception $ex) {
            $phoneNumberUtil = null;
            throw new Exception('手機格式錯誤');
        }

        if ($phoneNumberObject) {
            $result = $phoneNumberUtil->isValidNumber($phoneNumberObject);

            if (!$result) {
                throw new Exception('手機格式錯誤');
            }
        }
        if ($onlyTw && $phoneNumberObject->getCountryCode() != "886") {
            throw new Exception('目前僅支援台灣手機門號');
        }

        $result = [
            "country_code" => $phoneNumberObject->getCountryCode(),
            "number"       => $phoneNumberObject->getNationalNumber(),
            "full_format"  => "+" . $phoneNumberObject->getCountryCode() . $phoneNumberObject->getNationalNumber(),
        ];

        return $result;
    }

    //設定購物車返回網址
    public function setCartCloseUrl($url) {
        Yii::$app->session->set("cartCloseUrl", $url);
    }

    //取得購物車返回網址
    public function getCartCloseUrl() {
        $url = Yii::$app->session->get("cartCloseUrl");
        if (empty($url) || strpos($url, "member") !== false || strpos($url, "checkout") !== false || strpos($url, "cart") !== false || parse_url($url)['host'] != Yii::$app->request->serverName) {
            if (Yii::$app->controller->singleSale) {
                return "/";
            } else {
                return "/product";
            }
        } else {
            return $url;
        }
    }

    //訂單身份紀錄
    public function setOrderSession($phone) {
        Yii::$app->session->set("orderSession", $phone);
    }

    //取得訂單身份紀錄
    public function getOrderSession() {
        return Yii::$app->session->get("orderSession");
    }

    public function getMediaThumb($mediaType, $mediaUrl, $defaultImg) {
        if ($mediaType == 'youtube') {
            $code = explode("/", $mediaUrl);
            $code = $code[count($code) - 1];
            return "https://img.youtube.com/vi/{$code}/hqdefault.jpg";
        } else {
            return $defaultImg;
        }
    }


    function isIos() {
        $iPod = strpos($_SERVER['HTTP_USER_AGENT'], "iPod");
        $iPhone = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $iPad = strpos($_SERVER['HTTP_USER_AGENT'], "iPad");

        if ($iPad || $iPhone || $iPod) {
            return true;
        } else {
            return false;
        }
    }

    public function getImageSize($fileUrl) {
        if (strpos($fileUrl, Yii::$app->params["staticFileUrl"]) !== false) {
            $filePath = Yii::getAlias('@common') . '/web' . str_replace(Yii::$app->params["staticFileUrl"], "", $fileUrl);
            $info = getimagesize($filePath);
        } else {
            $info = getimagesize($fileUrl);
        }
        $result = [
            'width'  => $info[0],
            'height' => $info[1],
            'mime'   => $info['mime'],
        ];
        return $result;
    }

    function getWebpToSeoImgUrl($fileUrl, $quality = 90) {
        try {
            $pathInfo = pathinfo($fileUrl);
            $ext = $pathInfo['extension'];
            if ($ext != 'webp') {
                //非webp直接回傳
                return $fileUrl;
            }
            $filePath = Yii::getAlias('@common') . '/web' . str_replace(Yii::$app->params["staticFileUrl"], "", $fileUrl);

            $im = imagecreatefromwebp($filePath);
            $newPath = Yii::getAlias('@common') . '/web/uploads/seo/' . $pathInfo['filename'] . ".jpg";
            $newUrl = Yii::$app->params["staticFileUrl"] . '/uploads/seo/' . $pathInfo['filename'] . ".jpg";
            if (!file_exists($newPath)) {
                $result = imagejpeg($im, $newPath, $quality);
                imagedestroy($im);
                if (!$result) {
                    return $fileUrl;
                }
            }
            return $newUrl;
        } catch (Exception $ex) {
            return $fileUrl;
        }
    }

    public function imageFileToBase64($path){
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = base64_encode(file_get_contents($path));
        return "data:image/{$type};base64, {$data}";
    }
}
