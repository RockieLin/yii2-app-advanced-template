<?php
namespace admin\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use admin\models\forms\LoginForm;
use common\models\entities\Admin;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use \Exception;

/**
 * UserController implements the CRUD actions for User model.
 */
class AdminController extends BaseController {

    public function init() {
        Yii::$app->params["tmpSubMenuId"] = "admin";
        $this->title = '後台帳號管理';
    }

    public function actionIndex() {
        $searchModel = new \admin\models\search\AdminSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->params["_tmpRoles"] = Admin::getRoles();
        return $this->render('index', [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new Admin();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if ($model->save()) {
                Yii::$app->session->setFlash('success', "新增完成");
                return $this->redirect([
                            'update',
                            'id' => $model->id]);
            }
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($id) {
        $hash = true;
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if (empty($model->password)) {
                $model->password = $model->getOldAttributes()["password"];
                $hash = false;
            }

            if ($model->validate()) {
                if ($hash) {
                    $model->password = md5($model->password);
                }


                if ($model->save()) {
                    Yii::$app->session->setFlash('success', "修改完成");
                    return $this->redirect([
                                'update',
                                'id' => $model->id]);
                }
            }
        }

        $model->password = null;

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    public function actionStatus($id) {
        $model = $this->findModel($id);
        $model->status = 1 - $model->status;
        $model->save();

        return $this->redirect([
                    'index']);
    }

    public function actionDelete($id) {
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success', "刪除完成");

        return $this->redirect([
                    'index']);
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->redirect(Url::toRoute('user/login'));
    }

    public function actionRole() {
        $this->title = '角色管理';
        $roles = Yii::$app->authManager->getRoles();

        $data = [
                ];
        foreach ($roles as $key => $value) {
            $data[] = [
                'id'         => $value->name,
                'name'       => $value->description,
                'updated_at' => $value->updatedAt,
            ];
        }

        $dataProvider = new ArrayDataProvider([
            'allModels'  => $data,
            'pagination' => [
                'pageSize' => 30,
            ],
            'sort'       => [
                'attributes' => [
                    'id',
                    'name'],
            ],
        ]);

        return $this->render('role', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRoleCreate() {
        $this->layout = "modal";
        Yii::$app->params["modalWidth"] = "40%";

        $model = new \admin\models\forms\RoleModel();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $auth = Yii::$app->authManager;
                $role = $auth->createRole($model->id);
                $role->description = $model->name;

                if ($auth->add($role)) {
                    Yii::$app->session->setFlash('success', "新增完成");
                    $this->layout = "base";
                    return $this->render('@app/views/top_reload');
                }
            } catch (Exception $ex) {
                $model->addError("error", $ex->getMessage());
            }
        }

        return $this->render('roleForm', [
                    'model'      => $model,
                    'disabledId' => false,
        ]);
    }

    public function actionRoleUpdate($id) {
        $this->layout = "modal";
        Yii::$app->params["modalWidth"] = "40%";

        $auth = Yii::$app->authManager;
        $role = $auth->getRole($id);
        $model = new \admin\models\forms\RoleModel();
        $model->id = $role->name;
        $model->name = $role->description;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $role->description = $model->name;

                if ($auth->update($model->id, $role)) {
                    Yii::$app->session->setFlash('success', "修改完成");
                    $this->layout = "base";
                    return $this->render('@app/views/top_reload');
                }
            } catch (Exception $ex) {
                $model->addError("error", $ex->getMessage());
            }
        }

        return $this->render('roleForm', [
                    'model'      => $model,
                    'disabledId' => true,
        ]);
    }

    public function actionRoleDelete($id) {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($id);

        //user revokeRole
        foreach (Admin::find()->where([
            "role" => $role->name])->each() as $user) {
            $user->role = 0;
            $user->save();
        }
        //remove child
        $auth->removeChildren($role);
        //remove role
        $auth->remove($role);

        Yii::$app->session->setFlash('success', "刪除完成,所有套用此角色的帳號需重新設定權限!");

        return $this->redirect([
                    'role']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
