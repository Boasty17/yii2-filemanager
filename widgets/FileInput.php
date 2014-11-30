<?php
namespace pendalf89\filemanager\widgets;

use Yii;
use yii\helpers\Html;
use yii\widgets\InputWidget;
use pendalf89\filemanager\assets\FileInputAsset;

class FileInput extends InputWidget
{
    public $template = '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>';

    public $buttonTag = 'button';

    public $buttonName = 'Browse';

    public $buttonOptions = ['class' => 'btn btn-default'];

    public $options = ['class' => 'form-control'];

    public function init()
    {
        parent::init();

        if (empty($this->buttonOptions['id'])) {
            $this->buttonOptions['id'] = $this->options['id'] . '-btn';
        }
    }

    /**
     * Runs the widget.
     */
    public function run()
    {
        if ($this->hasModel()) {
            $replace['{input}'] = Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            $replace['{input}'] = Html::textInput($this->name, $this->value, $this->options);
        }

        $replace['{button}'] = Html::tag($this->buttonTag, $this->buttonName, $this->buttonOptions);

        FileInputAsset::register($this->getView());

        $this->getView()->registerJs(
            '$("#' . $this->buttonOptions['id'] . '").on("click", function(e) {
                e.preventDefault();
                var iframe = \'<iframe src="' . Yii::$app->urlManager->createUrl(['filemanager/file/filemanager']) . '" id="filemanager-frame" frameborder="0"></iframe>\';
                $("#filemanager-modal .modal-body").html(iframe);
                $("#filemanager-modal").modal("show");
            });'
        );

        $modal = $this->renderFile('@vendor/pendalf89/yii2-filemanager/views/file/modal.php');

        return strtr($this->template, $replace) . $modal;
    }
}