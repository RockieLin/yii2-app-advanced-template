<?php
namespace admin\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

/**
 * UserController implements the CRUD actions for User model.
 */
class PermissionController extends BaseController {

    public function init() {
        Yii::$app->params["tmpSubMenuId"] = "user";
        $this->title = "權限管理";
        $this->enableCsrfValidation = false;
    }

    public function actionIndex() {
        $dataAry = $columns = [
                ];

        $auth = Yii::$app->authManager;
        $cnt = 1;
        foreach ($auth->getPermissions() as $key => $value) {
            $_tmp = array(
                'id'    => $cnt,
                'label' => $value->description,
            );
            foreach ($auth->getRoles() as $role) {
                if ($role->name == 1) {
                    $_tmp["assign_" . $role->name] = Html::checkbox("assign_item", true, [
                                "disabled" => true]);
                } else {
                    $_tmp["assign_" . $role->name] = Html::checkbox("assign_item", $auth->hasChild($auth->getRole($role->name), $value), [
                                "class"     => "role-assign",
                                "data-name" => $value->name,
                                "data-role" => $role->name]);
                }
            }

            $dataAry[] = $_tmp;
            $cnt++;
        }

        $dataProvider = new ArrayDataProvider([
            'allModels'  => $dataAry,
            'pagination' => false]);

        $columns[] = array(
            "header"  => "功能",
            'format'  => 'raw',
            'options' => [
                "style" => "width:20%;"],
            "value"   => function($data) {
                return $data["label"];
            }
        );
        foreach ($auth->getRoles() as $role) {
            $name = $role->name;
            $columns[] = array(
                "header" => $role->description,
                'format' => 'raw',
                "value"  => function($data) use ($name) {
                    return $data["assign_" . $name];
                }
            );
        }

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    "columns"      => $columns
        ]);
    }

    public function actionUpdate() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $item = Yii::$app->request->post("item");
        $role = Yii::$app->request->post("role");
        if (!($item || $role)) {
            return "缺少參數";
        }

        $auth = Yii::$app->authManager;
        $item = $auth->getPermission($item);
        $role = $auth->getRole($role);
        if ($auth->hasChild($role, $item)) {
            //刪除
            $auth->removeChild($role, $item);
        } else {
            //新增
            $auth->addChild($role, $item);
        }

        //清cache
        Yii::$app->cache->flush();

        return "success";
    }

}
