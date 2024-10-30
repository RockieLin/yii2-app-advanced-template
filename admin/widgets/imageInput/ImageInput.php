<?php

namespace admin\widgets\imageInput;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class ImageInput extends InputWidget {

    public $attribute = 'image';
    public $value;

    public $recommandSize = [
        'width'  => 200,
        'height' => 100,
    ];
    public $fileInputStyle = 'height:0px; opacity:0;display:none;';
    public $fileInputAccept = 'image/*';
    public $showLabel = true;
    public $previewImageMaxWidth = 186;

    /**
     * @inheritdoc
     */
    public function run() {
        parent::run();
        $this->registerAssets();

        echo $this->renderInput();
    }

    /**
     * Renders the input
     *
     * @return string
     */
    protected function renderInput() {
        $this->view->registerJs("imageUploadPreview();");

        $options = ArrayHelper::merge($this->options, [
            'accept' => $this->fileInputAccept,
            'style'  => $this->fileInputStyle
        ]);

        $ratio = $this->recommandSize["height"] / $this->recommandSize["width"];
        $width = $this->previewImageMaxWidth;
        if ($ratio == 1) {
            $padding = ($ratio / 2 * 100) . "%";
        } else {
            $padding = ($ratio * 100) . "%";
        }
        $result = [];
        if ($this->showLabel) {
            $result[] = '<label class="control-label">' . $this->model->getAttributeLabel($this->attribute) . '</label><br/>';
        }
        if(!empty($this->attribute)){
            $previewImage = $this->model->getImage($this->attribute);
            $result[] = '<a href="' . $previewImage . '" target="_blank" class="imageLink center">';
            $result[] = '<div class="imageContent center" style="max-width:' . $width . 'px;background-image:url(\'' . $previewImage . '\');padding-bottom: ' . $padding . '">';
            $result[] = '</div>';
            $result[] = '</a>';
        }

        $result[] = '<button type="button" class="btn btn-info imgUploadButton">';
        $result[] = '<i class="fa fa-edit"></i> 選擇圖片<br/>' . "建議尺寸 " . $this->recommandSize['width'] . " x " . $this->recommandSize["height"];
        $result[] = '</button>';
        $result[] = '<div class="form-group">';
        $result[] = Html::activeFileInput($this->model, $this->attribute, $options);
        $result[] = Html::error($this->model, $this->attribute);
        $result[] = '</div>';
//        $result[] = '<div class="hide">';
//        $result[] = Html::activeHiddenInput($this->model, $this->attribute, ['id' => null]);
//        $result[] = '</div>';

        return implode("\n", $result);
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets() {
        $view = $this->getView();
        ImageInputAsset::register($view);
    }

}