<?php

namespace app\modules\androiddpu;

/**
 * androiddpu module definition class
 */
class Androiddpu extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\androiddpu\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->modules = [
            'v1' => [
                'class' => 'app\modules\androiddpu\v1\V1',
            ],
        ];
        // custom initialization code goes here
    }

}
