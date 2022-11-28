<?php

namespace app\modules\webservice\dataexchange;

/**
 * dataexchange module definition class
 */
class dataexchange extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\webservice\dataexchange\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        $this->modules = [
            'v1' => [
                'class' => 'app\modules\webservice\dataexchange\v1\V1',
            ],
        ];
        // custom initialization code goes here
    }

}
