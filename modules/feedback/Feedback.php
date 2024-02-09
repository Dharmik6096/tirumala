<?php

namespace app\modules\feedback;
use app\assets\FeedbackAssets;
use Yii;

/**
 * feedback module definition class
 */
class feedback extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\feedback\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        FeedbackAssets::register(Yii::$app->view);
        // custom initialization code goes here
    }
}
