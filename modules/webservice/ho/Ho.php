<?php

namespace app\modules\webservice\ho;

/**
 * bmc module definition class
 */
class Ho extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\webservice\ho\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->modules = [
            'v1' => [
                'class' => 'app\modules\webservice\ho\v1\V1',
            ],
            'v2' => [
                'class' => 'app\modules\webservice\ho\v2\V2',
            ],
        ];
    }

}
