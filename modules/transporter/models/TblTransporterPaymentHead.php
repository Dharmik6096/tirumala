<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_transporter_payment_head".
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
class TblTransporterPaymentHead extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_transporter_payment_head';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['transporter_payment_head', 'created_by', 'updated_by'], 'string'],
            [['type', 'is_active'], 'integer'],
            [['created_at', 'updated_at', 'union_code', 'is_default'], 'safe'],
            [['transporter_payment_head', 'type'], 'required'],
            [['is_default'], 'default', 'value' => 0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'transporter_payment_head_code' => Yii::t('app', 'Transporter Payment Head Code'),
            'transporter_payment_head' => Yii::t('app', 'Transporter Payment Head'),
            'type' => Yii::t('app', 'Type'),
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

    public function getPaymentHeadList($union) {
        $data = $this->find()
                ->select(['transporter_payment_head_code', 'transporter_payment_head'])
                ->where(['union_code' => $union, 'is_default' => 0])
                ->all();
        $value = [];
        if (!empty($data)) {
            $value = ArrayHelper::map($data, 'transporter_payment_head_code', 'transporter_payment_head');
        }
        return $value;
    }

}
