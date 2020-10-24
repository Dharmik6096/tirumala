<?php

namespace app\modules\collection\models;

use yii\base\Model;

class OnlineCollectionModel extends Model {

    public $search_date, $from_time, $to_time;

    function __construct() {
        
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['search_date', 'from_time', 'to_time'], 'required'],
                [['from_time', 'to_time'], 'match', 'pattern' => '/^(0[0-9]|1[0-9]|2[0-3]|[0-9]):[0-5][0-9]$/'],
        ];
    }

    public function attributeLabels() {
        return [
            'search_date' => \Yii::t('app', 'Date'),
            'from_time' => \Yii::t('app', 'From Time'),
            'to_time' => \Yii::t('app', 'To Time'),
        ];
    }

    public function search($params) {
        
    }

}
