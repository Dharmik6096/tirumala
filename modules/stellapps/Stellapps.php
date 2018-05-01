<?php

namespace app\modules\stellapps;

/**
 * stellapps module definition class
 */
class Stellapps extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\stellapps\controllers';
    public static $label = [
        'save_tmcc_member_list' => [
            'allow_update' => TRUE,
            'merge_key' => 'dcs_code',
            'data_key' => 'ex_member_code',
            'history_model' => 'TblMemberHistory',
            'update_on' => 'member_code',
        ],
        'save_tmcc_configs' => [
            'allow_update' => TRUE,
            'merge_key' => 'dcs_code',
            'data_key' => '',
            'history_model' => 'TblDcsConfigHistory',
            'update_on' => 'dcs_code',
        ]
    ];

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

// custom initialization code goes here
    }

    public static function getLabels($l) {
        return isset(self::$label[$l]) ? self::$label[$l] : NULL;
    }

}
