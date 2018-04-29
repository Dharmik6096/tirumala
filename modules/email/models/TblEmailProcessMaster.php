<?php

namespace app\modules\email\models;

use Yii;

/**
 * This is the model class for table "tbl_email_process_master".
 *
 * @property integer $rule_id
 * @property string $process_name
 * @property string $depends_on
 * @property string $sp_name
 * @property integer $is_active
 */
class TblEmailProcessMaster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_email_process_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['process_name', 'depends_on', 'sp_name'], 'string'],
            [['is_active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rule_id' => Yii::t('app', 'Rule ID'),
            'process_name' => Yii::t('app', 'Process Name'),
            'depends_on' => Yii::t('app', 'Depends On'),
            'sp_name' => Yii::t('app', 'Sp Name'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }
}
