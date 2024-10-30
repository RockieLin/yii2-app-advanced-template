<?php

namespace admin\controllers;

use common\models\entities\Config;
use common\models\search\ConfigSearch;
use Yii;
use yii\web\NotFoundHttpException;

class ConfigController extends BaseController {
    public function init() {
        parent::init();
    }

    /**
     * Lists all Config models.
     * @return mixed
     */
    public function actionIndex($category = 'site') {
        $searchModel = new ConfigSearch();
        $searchModel->category = $category;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Room model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($category, $type = 'text') {
        $this->layout = "modal";
        Yii::$app->params["modalWidth"] = "60%";

        $model = new Config();
        $model->type = $type;
        $model->category = $category;
        $model->scenario = $type;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->scenario == 'image') {
                $type = 'common';
                $attr = "content";
                $model->saveRawImage($attr, $type, true);
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', "新增完成");

                $this->layout = "loading";
                return $this->render('@app/views/top_reload');
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Room model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->layout = "modal";
        Yii::$app->params["modalWidth"] = "60%";

        $model = $this->findModel($id);
        $model->scenario = $model->type;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->scenario == 'image') {
                $type = 'common';
                $attr = "content";
                $model->saveRawImage($attr, $type, true);
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', "修改完成");

                $this->layout = "loading";
                return $this->render('@app/views/top_reload');
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    protected function findModel($id) {
        if (($model = Config::getCache($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t("app", "no_data"));
        }
    }

}
