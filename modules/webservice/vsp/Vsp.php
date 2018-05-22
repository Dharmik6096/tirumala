<?php

namespace app\modules\webservice\vsp;

/**
 * vsp module definition class
 */
class Vsp extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\webservice\vsp\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->modules = [
            'v1' => [
                'class' => 'app\modules\webservice\vsp\v1\V1',
            ],
        ];
    }

}
