<?php

namespace app\modules\dynamicreport\models;

use Yii;

/**
 * This is the model class for table "tbl_control_list".
 *
 * @property integer $control_code
 * @property string $control_name
 * @property string $control_label
 * @property string $control_type
 */
class TblControlList extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_control_list';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['control_name', 'control_label', 'control_type'], 'string'],
            [['control_sp', 'control_param'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'control_code' => Yii::t('app', 'Control Code'),
            'control_name' => Yii::t('app', 'Control Name'),
            'control_label' => Yii::t('app', 'Control Label'),
            'control_type' => Yii::t('app', 'Control Type'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblControlListQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblControlListQuery(get_called_class());
    }

}
