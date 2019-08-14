<?php

namespace app\modules\webservice\eipl;

/**
 * eipl module definition class
 */
class Eipl extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\webservice\eipl\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        $this->modules = [
            'v1' => [
                'class' => 'app\modules\webservice\eipl\v1\V1',
            ],
        ];
    }

}
