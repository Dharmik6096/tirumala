<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_union_dpu_config".
 *
 * @property integer $dpu_config_id
 * @property string $eipl_code
 * @property string $union_code
 * @property integer $dpu_type
 * @property string $dpu_key
 */
class TblUnionDpuConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_union_dpu_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['eipl_code', 'union_code', 'dpu_key'], 'string'],
            [['dpu_type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dpu_config_id' => Yii::t('app', 'Dpu Config ID'),
            'eipl_code' => Yii::t('app', 'Eipl Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'dpu_type' => Yii::t('app', 'Dpu Type'),
            'dpu_key' => Yii::t('app', 'Dpu Key'),
        ];
    }
}
