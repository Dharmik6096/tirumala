<?php

namespace app\modules\clienterp;

/**
 * clienterp module definition class
 */
class Clienterp extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\clienterp\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        $this->modules = [
            'vka' => [
                'class' => 'app\modules\clienterp\vka\Vka',
            ],
        ];
    }

}
