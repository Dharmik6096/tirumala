<?php

namespace app\modules\webservice\supervisor;

/**
 * supervisor module definition class
 */
class Supervisor extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\webservice\supervisor\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->modules = [
            'v1' => [
                'class' => 'app\modules\webservice\supervisor\v1\V1',
            ],
        ];
    }

}
