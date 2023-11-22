<?php

namespace app\modules\tankermovement\models;

use Yii;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_payment_head".
 *
 * @property integer $transporter_payment_head_code
 * @property string $transporter_payment_head
 * @property integer $type
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblPaymentHead extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_payment_head';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'payment_head_name', 'created_at', 'updated_at', 'created_by', 'updated_by', 'sequence_no', 'payment_head_for'], 'safe'],
            [['payment_head_type', 'payment_head_name'], 'required'],
            [['payment_head_name'], function ($attribute, $params) {
                    Yii::$app->general->validateAlphaNumber($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            [['sequence_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildateNumericField($this, $attribute, $params);
                }],
            [['is_active'], 'default', 'value' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'payment_head_code' => Yii::t('app', 'Payment Head Code'),
            'payment_head_name' => Yii::t('app', 'Payment Head Name'),
            'payment_head_type' => Yii::t('app', 'Type'),
            'sequence_no' => Yii::t('app', 'Sequence No'),
            'payment_head_for' => Yii::t('app', 'Payment Head For'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'union_code' => Yii::t('app', 'Union'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

}
