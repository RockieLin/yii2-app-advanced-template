<?php namespace admin\controllers;

use Yii;
use common\models\entities\Announce;
use common\models\search\AnnounceSearch;
use yii\web\NotFoundHttpException;

/**
 * AnnounceController implements the CRUD actions for Announce model.
 */
class AnnounceController extends BaseController {

    public function init() {
        $this->title = '資訊發佈';
    }

    public function actionIndex() {

        $searchModel = new AnnounceSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new Announce();
        $today = time();
        $model->start_time = date("Y-m-d H:i", $today);
        $model->end_time = date("Y-m-d H:i", $today + 86400 * 8 - 1);  //一週
        $model->time_range = $model->start_time . " - " . $model->end_time;

        if ($model->load(Yii::$app->request->post())) {
            $uploadPath = Yii::getAlias('@common') . "/web";

            $attr = "image";
            $type = "announce";
            $_image = \yii\web\UploadedFile::getInstance($model, $attr);
            if ($_image) {
                $imageFileName = "/uploads/{$type}/" . uniqid() . '.';
                $input = $imageFileName . $_image->extension;
                $output = $imageFileName . "jpg";

                $_image->saveAs($uploadPath . $input);
                //縮圖
                $result = Yii::$app->tool->thumbnailByWithRatio($uploadPath . $input, $uploadPath . $output, $type);
                if ($uploadPath . $input != $uploadPath . $output) {
                    unlink($uploadPath . $input);
                }

                if ($result) {
                    $model->addError($attr, $model->getAttributeLabel($attr) . "檔案格式有誤,請重新上傳檔案");
                } else {
                    $model->$attr = $output;
                }
            }

            if ($model->validate() && $model->save()) {
                Yii::$app->session->setFlash('success', "新增完成");

                return $this->redirect([
                    'update',
                    'id' => $model->id
                ]);
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
        $model = $this->findModel($id);

        $model->start_time = date("Y-m-d H:i", $model->start_time);
        $model->end_time = date("Y-m-d H:i", $model->end_time);
        $model->time_range = $model->start_time . " - " . $model->end_time;

        if ($model->load(Yii::$app->request->post())) {
            $uploadPath = Yii::getAlias('@common') . "/web";

            $attr = "image";
            $type = "announcement";
            $_image = \yii\web\UploadedFile::getInstance($model, $attr);
            if ($_image) {
                $imageFileName = "/uploads/{$type}/" . uniqid() . '.';
                $input = $imageFileName . $_image->extension;
                $output = $imageFileName . "jpg";

                $_image->saveAs($uploadPath . $input);
                //縮圖
                $result = Yii::$app->tool->thumbnailByWithRatio($uploadPath . $input, $uploadPath . $output, $type);
                if ($uploadPath . $input != $uploadPath . $output) {
                    unlink($uploadPath . $input);
                }

                if ($result) {
                    $model->addError($type, $model->getAttributeLabel($attr) . "檔案格式有誤,請重新上傳檔案");
                } else {
                    $model->$attr = $output;
                }
            }

            if ($model->validate() && $model->save()) {
                Yii::$app->session->setFlash('success', "修改完成");
                return $this->redirect([
                    'update',
                    'id' => $model->id
                ]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        $model = $this->findModel($id);
        $model->status = -1;
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Announce model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Announce the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Announce::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actions() {
        return [
            'images-get' => [
                'class' => 'vova07\imperavi\actions\GetImagesAction',
                'url' => Yii::$app->params["staticFileUrl"] . '/uploads/announcement/',
                'path' => '@common/web/uploads/announcement',
            ],
            'image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadFileAction',
                'url' => Yii::$app->params["staticFileUrl"] . '/uploads/announcement/',
                'path' => '@common/web/uploads/announcement'
            ],
        ];
    }

}
