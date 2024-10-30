<?php namespace admin\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use admin\models\forms\LoginForm;
use common\models\entities\Member;
use yii\helpers\Url;

/**
 * UserController implements the CRUD actions for User model.
 */
class MemberController extends BaseController {

    public function init() {
        parent::init();
    }

    public function actionIndex() {
        $searchModel = new \admin\models\search\MemberSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Member();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if ($model->save()) {
                Yii::$app->session->setFlash('success', "新增完成");
                return $this->redirect(['update',
                            'id' => $model->id]);
            }
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
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
                    return $this->redirect(['update',
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
        if ($model->status == Member::STATUS_DISABLE) {
            $model->status = Member::STATUS_EMAIL_VALIDATE;
        } else {
            $model->status = Member::STATUS_DISABLE;
        }

        $model->save();

        return $this->redirect(['index']);
    }

    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id) {
        if (($model = Member::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
