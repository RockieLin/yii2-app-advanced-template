<?php

namespace admin\widgets;

use Yii;

class Menu extends \yii\base\Widget {
    public $menuData;
    public $category = "permission";

    public function init() {
        parent::init();
        $user = Yii::$app->user;

        $result = [];
        foreach ($this->menuData as $_key => $_menu) {

            if ($_menu["menuId"] == null) {
                //一層
                $_node = $_menu["nodes"][0];
                $tmp = null;
                if ($user->can($_node["permission"])) {
                    $tmp .= "<li class='" . $this->setNavActive($_node) . "'>";
                    $tmp .= "<a target='" . ($_node["target"] ?? '_self') . "' href='" . Yii::$app->tool->toBaseUrl(["/" . str_replace(".", "/", $_node["permission"])]) . "'>";
                    $tmp .= "<span>" . $_node["name"] . "</span>";
                    $tmp .= "</a>";
                    $tmp .= "</li>";
                }
                $result[$_key] = $tmp;
            } else {
                //多層
                $passMenu = false;
                $tmp = "<li class='hasSubmenu'>";
                $tmp .= "<a href='#{$_menu["menuId"]}' data-toggle='collapse' class=''>";
                $tmp .= "<span>" . $_menu["menuName"] . "</span>";
                $tmp .= "</a>";
                $tmp .= "<ul id='{$_menu["menuId"]}' class='collapse'>";
                foreach ($_menu["nodes"] as $_node) {
                    if ($user->can($_node["permission"])) {
                        $passMenu = true;
                        $active = $this->setNavActive($_node);
                        $tmp .= "<li class='{$active}'>";
                        $tmp .= "<a target='" . ($_node["target"] ?? '_self') . "' href='" . Yii::$app->tool->toBaseUrl(["/" . str_replace(".", "/", $_node["permission"])]) . "'>";
                        $tmp .= "<span>" . $_node["name"] . "</span>";
                        $tmp .= "</a>";
                        $tmp .= "</li>";

                        if ($active) {
                            $this->getView()->registerJs("$('#{$_menu["menuId"]}').collapse()");
                        }
                    }
                }
                $tmp .= "</ul>";
                $tmp .= "</li>";

                if ($passMenu) {
                    $result[$_key] = $tmp;
                }
            }
        }

        echo implode("", $result);
    }

    /*
     * 設定後台選單active
     */
    private function setNavActive($node, $returnString = "active") {
        $controller = Yii::$app->controller;
        $name = strtolower($controller->id . "." . $controller->action->id);
        $all = strtolower($controller->id . ".*");

        foreach ($node['activePermission'] as $_permission) {
            if ($name == $_permission || $all == $_permission) {
                $name = strip_tags($node["name"]);
                $this->getView()->registerJs('$("#menuTitle").text("' . $name . '")');
                Yii::$app->controller->title = $name;
                return $returnString;
            }
        }
        return null;
    }

}
