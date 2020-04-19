<?php namespace common\components;

use common\models\entities\Config;
use common\models\entities\EmailCheckCodes;
use common\models\entities\Member;
use common\models\entities\Performance;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use common\models\entities\PhoneCheckCodes;
use SteelyWing\Chinese\Chinese;
use yii\httpclient\Client;
use yii\web\HttpException;
use Exception;

class Tool extends Component {
    protected $decimals = 4;

    public function toBaseUrl($route, $fullPath = false) {
        return Url::to($route, $fullPath);
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

        return Url::current($paramAry, $scheme);
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

    public function sendMail($toEmail, $toBcc = null, $subject = null, $parameter = array(), $viewName = "default") {
        $email = Yii::$app->setting->get("sendFromMail");
        $response = Yii::$app->mailer->compose($viewName, $parameter)
            ->setFrom([$email => $email])
            ->setTo($toEmail)
            ->setSubject($subject);

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
        $emailModel->attributes = array(
            "member_id" => $user->id,
            "check_code" => $checkCode,
            'email' => $user->email,
            'type' => $type,
            'other' => $other,
        );

        if (!$emailModel->save()) {
            throw new \yii\web\HttpException(400, $this->formatErrorMsg($emailModel->getErrors()));
        }

        if ($type == EmailCheckCodes::TYPE_REGIST) {
            $subject = Yii::t('app', 'mailCheckCodeTitle')["register"];
            $view = "emailcheck_" . Yii::$app->language;
            $url = $this->toBaseUrl([
                "user/regist-email-confirm",
                "checkCode" => $checkCode,
                "language" => Yii::$app->language
            ], true);
        } elseif ($type == EmailCheckCodes::TYPE_FORGET_PASSWORD) {
            $subject = Yii::t('app', 'mailCheckCodeTitle')["forgetPwd"];
            $view = "emailforgetpwd_" . Yii::$app->language;
            $url = $this->toBaseUrl([
                "user/forget-pwd-confirm",
                "checkCode" => $checkCode,
                "language" => Yii::$app->language
            ], true);
        } else {
            throw new \yii\web\HttpException(400, "認證碼類型有誤");
        }

        return $this->sendMail($user->email, $user->email, $subject, array(
            "user" => $user,
            "url" => $url,
            "other" => $other,
        ), $view);
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

            \yii\imagine\Image::frame($target, (int) $_margin, "FFF")->save($target);

            $newWidth = $_width;
            $newHeight = $_height;
        } else {
            //寬度太長先縮寬度
            $newWidth = $_thumbWidth;
            $newHeight = (int) (($_thumbWidth / $_width) * $_height);
            \yii\imagine\Image::resize($target, $newWidth, $newHeight)
                ->save($target);

            if ($newHeight < $_thumbHeight) {
                //高度不夠加padding 再縮圖
                $_margin = ($_thumbHeight - $newHeight) / 2;
                \yii\imagine\Image::frame($target, (int) $_margin, "FFF")->save($target);
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
        $array = json_decode($string, true);
        return !empty($string) && is_string($string) && is_array($array) && !empty($array) && json_last_error() == 0;
    }

    public function twTocn($string) {
        $chinese = new Chinese();
        return $chinese->to(Chinese::CHS, $string);
    }

    public function cnTotw($string) {
        $chinese = new Chinese();
        return $chinese->to(Chinese::CHT, $string);
    }

    /**
     * @param array $paths
     * @param $currentUrl
     * @param $returnString
     * @return string
     */
    public function showNavClass(Array $paths, $currentUrl, $returnString) {
        $result = "";
        for ($i = 0; $i < count($paths); $i++) {
            $arrUrlPath = explode("?", $currentUrl);
            if ($arrUrlPath[0] != $paths[$i]) {
                continue;
            }
            $paths[$i] = str_replace('/', '\/', $paths[$i]);
            if (preg_match('/' . $paths[$i] . '\?{0,1}\S{0,}$/', $currentUrl)) {
                $result = $returnString;
                break;
            }
        }
        return $result;
    }

    /**
     * 由useragent拆解登入資訊
     * @param $usString
     * @return array
     */
    public function parseUserAgent($u_agent) {
        if ($u_agent === null && isset($_SERVER['HTTP_USER_AGENT'])) {
            $u_agent = $_SERVER['HTTP_USER_AGENT'];
        }
        if ($u_agent === null) {
            throw new \InvalidArgumentException('parse_user_agent requires a user agent');
        }
        $platform = null;
        $browser = null;
        $version = null;
        $empty = array('platform' => $platform, 'browser' => $browser, 'version' => $version);
        if (!$u_agent) {
            return $empty;
        }
        if (preg_match('/\((.*?)\)/m', $u_agent, $parent_matches)) {
            preg_match_all('/(?P<platform>BB\d+;|Android|CrOS|Tizen|iPhone|iPad|iPod|Linux|(Open|Net|Free)BSD|Macintosh|Windows(\ Phone)?|Silk|linux-gnu|BlackBerry|PlayBook|X11|(New\ )?Nintendo\ (WiiU?|3?DS|Switch)|Xbox(\ One)?)
				(?:\ [^;]*)?
				(?:;|$)/imx', $parent_matches[1], $result);
            $priority = array(
                'Xbox One', 'Xbox', 'Windows Phone', 'Tizen', 'Android', 'FreeBSD', 'NetBSD', 'OpenBSD', 'CrOS',
                'X11'
            );
            $result['platform'] = array_unique($result['platform']);
            if (count($result['platform']) > 1) {
                if ($keys = array_intersect($priority, $result['platform'])) {
                    $platform = reset($keys);
                } else {
                    $platform = $result['platform'][0];
                }
            } elseif (isset($result['platform'][0])) {
                $platform = $result['platform'][0];
            }
        }
        if ($platform == 'linux-gnu' || $platform == 'X11') {
            $platform = 'Linux';
        } elseif ($platform == 'CrOS') {
            $platform = 'Chrome OS';
        }
        preg_match_all('%(?P<browser>Camino|Kindle(\ Fire)?|Firefox|Iceweasel|IceCat|Safari|MSIE|Trident|AppleWebKit|
				TizenBrowser|(?:Headless)?Chrome|YaBrowser|Vivaldi|IEMobile|Opera|OPR|Silk|Midori|Edge|Edg|CriOS|UCBrowser|Puffin|OculusBrowser|SamsungBrowser|
				Baiduspider|Googlebot|YandexBot|bingbot|Lynx|Version|Wget|curl|
				Valve\ Steam\ Tenfoot|
				NintendoBrowser|PLAYSTATION\ (\d|Vita)+)
				(?:\)?;?)
				(?:(?:[:/ ])(?P<version>[0-9A-Z.]+)|/(?:[A-Z]*))%ix',
            $u_agent, $result);
        // If nothing matched, return null (to avoid undefined index errors)
        if (!isset($result['browser'][0]) || !isset($result['version'][0])) {
            if (preg_match('%^(?!Mozilla)(?P<browser>[A-Z0-9\-]+)(/(?P<version>[0-9A-Z.]+))?%ix', $u_agent, $result)) {
                return array(
                    'platform' => $platform ?: null, 'browser' => $result['browser'],
                    'version' => isset($result['version']) ? $result['version'] ?: null : null
                );
            }
            return $empty;
        }
        if (preg_match('/rv:(?P<version>[0-9A-Z.]+)/i', $u_agent, $rv_result)) {
            $rv_result = $rv_result['version'];
        }
        $browser = $result['browser'][0];
        $version = $result['version'][0];
        $lowerBrowser = array_map('strtolower', $result['browser']);
        $find = function ($search, &$key, &$value = null) use ($lowerBrowser) {
            $search = (array) $search;
            foreach ($search as $val) {
                $xkey = array_search(strtolower($val), $lowerBrowser);
                if ($xkey !== false) {
                    $value = $val;
                    $key = $xkey;
                    return true;
                }
            }
            return false;
        };
        $key = 0;
        $val = '';
        if ($browser == 'Iceweasel' || strtolower($browser) == 'icecat') {
            $browser = 'Firefox';
        } elseif ($find('Playstation Vita', $key)) {
            $platform = 'PlayStation Vita';
            $browser = 'Browser';
        } elseif ($find(array('Kindle Fire', 'Silk'), $key, $val)) {
            $browser = $val == 'Silk' ? 'Silk' : 'Kindle';
            $platform = 'Kindle Fire';
            if (!($version = $result['version'][$key]) || !is_numeric($version[0])) {
                $version = $result['version'][array_search('Version', $result['browser'])];
            }
        } elseif ($find('NintendoBrowser', $key) || $platform == 'Nintendo 3DS') {
            $browser = 'NintendoBrowser';
            $version = $result['version'][$key];
        } elseif ($find('Kindle', $key, $platform)) {
            $browser = $result['browser'][$key];
            $version = $result['version'][$key];
        } elseif ($find('OPR', $key)) {
            $browser = 'Opera Next';
            $version = $result['version'][$key];
        } elseif ($find('Opera', $key, $browser)) {
            $find('Version', $key);
            $version = $result['version'][$key];
        } elseif ($find('Puffin', $key, $browser)) {
            $version = $result['version'][$key];
            if (strlen($version) > 3) {
                $part = substr($version, -2);
                if (ctype_upper($part)) {
                    $version = substr($version, 0, -2);
                    $flags = array(
                        'IP' => 'iPhone', 'IT' => 'iPad', 'AP' => 'Android', 'AT' => 'Android',
                        'WP' => 'Windows Phone', 'WT' => 'Windows'
                    );
                    if (isset($flags[$part])) {
                        $platform = $flags[$part];
                    }
                }
            }
        } elseif ($find('YaBrowser', $key, $browser)) {
            $browser = 'Yandex';
            $version = $result['version'][$key];
        } elseif ($find(array('Edge', 'Edg'), $key, $browser)) {
            $browser = 'Edge';
            $version = $result['version'][$key];
        } elseif ($find(array(
            'IEMobile', 'Midori', 'Vivaldi', 'OculusBrowser', 'SamsungBrowser', 'Valve Steam Tenfoot', 'Chrome',
            'HeadlessChrome'
        ), $key, $browser)) {
            $version = $result['version'][$key];
        } elseif ($rv_result && $find('Trident', $key)) {
            $browser = 'MSIE';
            $version = $rv_result;
        } elseif ($find('UCBrowser', $key)) {
            $browser = 'UC Browser';
            $version = $result['version'][$key];
        } elseif ($find('CriOS', $key)) {
            $browser = 'Chrome';
            $version = $result['version'][$key];
        } elseif ($browser == 'AppleWebKit') {
            if ($platform == 'Android') {
                $browser = 'Android Browser';
            } elseif (strpos($platform, 'BB') === 0) {
                $browser = 'BlackBerry Browser';
                $platform = 'BlackBerry';
            } elseif ($platform == 'BlackBerry' || $platform == 'PlayBook') {
                $browser = 'BlackBerry Browser';
            } else {
                $find('Safari', $key, $browser) || $find('TizenBrowser', $key, $browser);
            }
            $find('Version', $key);
            $version = $result['version'][$key];
        } elseif ($pKey = preg_grep('/playstation \d/i', $result['browser'])) {
            $pKey = reset($pKey);
            $platform = 'PlayStation ' . preg_replace('/\D/', '', $pKey);
            $browser = 'NetFront';
        }
        return array('platform' => $platform ?: null, 'browser' => $browser ?: null, 'version' => $version ?: null);

    }
}
