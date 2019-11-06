<?php

namespace app\modules\embededdpu;

/**
 * embededdpu module definition class
 */
class Embededdpu extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\embededdpu\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->modules = [
            'v1' => [
                'class' => 'app\modules\embededdpu\v1\V1',
            ],
        ];
        // custom initialization code goes here
    }

}
