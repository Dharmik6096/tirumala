<?php

namespace app\modules\webservice\member;

/**
 * member module definition class
 */
class Member extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\webservice\member\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->modules = [
            'v1' => [
                'class' => 'app\modules\webservice\member\v1\V1',
            ],
        ];
    }

}
