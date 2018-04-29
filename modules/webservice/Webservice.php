<?php

namespace app\modules\webservice;

/**
 * Webservice module definition class
 */
class Webservice extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\webservice\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->modules = [
            'v1' => [
                'class' => 'app\modules\webservice\v1\V1',
            ],
        ];
    }

}
