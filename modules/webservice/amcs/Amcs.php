<?php

namespace app\modules\webservice\amcs;

/**
 * amcs module definition class
 */
class Amcs extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\webservice\amcs\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->modules = [
            'v1' => [
                'class' => 'app\modules\webservice\amcs\v1\V1',
            ],
        ];
    }

}
