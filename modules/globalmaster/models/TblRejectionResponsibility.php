<?php

namespace app\modules\globalmaster\models;

use Yii;

/**
 * This is the model class for table "tbl_rejection_responsibility".
 *
 * @property integer $rejection_responsibility_code
 * @property string $responsibility_name
 * @property integer $is_active
 */
class TblRejectionResponsibility extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_rejection_responsibility';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active'], 'integer'],
            [['responsibility_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rejection_responsibility_code' => Yii::t('app', 'Rejection Responsibility Code'),
            'responsibility_name' => Yii::t('app', 'Responsibility Name'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }
}
