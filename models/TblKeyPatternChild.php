<?php

namespace app\models;

use Yii;

class TblKeyPatternChild extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_key_pattern_child';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['key_pattern_child_code','key_pattern_code','union_code','pattern_for','key_name','key_code_type','prefix_field','key_length','key_fix_length','key_reset_on','created_at','created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'key_pattern_child_code' => Yii::t('app', 'Key Pattern Child Code'),
            'key_pattern_code' => Yii::t('app', 'Key Pattern Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'pattern_for' => Yii::t('app', 'Pattern For'),
            'key_name' => Yii::t('app', 'Key Name'),
            'key_code_type' => Yii::t('app', 'Key Code Type'),
            'prefix_field' => Yii::t('app', 'Prefix Field'),
            'key_length' => Yii::t('app', 'Key Length'),
            'key_fix_length' => Yii::t('app', 'Key Fix Length'),
            'key_reset_on' => Yii::t('app', 'Key Reset On'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }    

}
