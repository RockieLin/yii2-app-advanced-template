<?php namespace front\components;

use yii\base\Component;

class SocialLogin extends Component {

    /**
     * 取id name email pic
     */
    public function getAttributes($type, $attributes) {
        switch ($type) {
            case "facebook":
                $result = array(
                    "id"    => (string)$attributes["id"],
                    "name"  => (string)$attributes["name"],
                    "email" => @$attributes["email"],
                    "image" => "https://graph.facebook.com/{$attributes["id"]}/picture",
                );
                break;
            case "google":
                $result = array(
                    "id"    => (string)$attributes["id"],
                    "name"  => (string)$attributes["displayName"],
                    "email" => @$attributes["emails"][0]["value"],
                    "image" => $attributes["image"]["url"],
                );
                break;
            case "twitter":
                $result = array(
                    "id"    => (string)$attributes["id"],
                    "name"  => (string)$attributes["name"],
                    "email" => @$attributes["email"],
                    "image" => $attributes["profile_image_url_https"],
                );
                break;
            case "qq":
                $result = array(
                    "id"    => (string)$attributes["id"],
                    "name"  => (string)$attributes["nickname"],
                    "email" => null,
                    "image" => $attributes["figureurl"],
                );
                break;
            case "weibo":
                $result = array(
                    "id"    => (string)$attributes["id"],
                    "name"  => (string)$attributes["name"],
                    "email" => null,
                    "image" => $attributes["profile_image_url"],
                );
                break;

            default:
                $result = array(
                    "id"    => null,
                    "name"  => null,
                    "email" => null,
                    "image" => null,
                );
                break;
        }

        return $result;
    }

}

?>