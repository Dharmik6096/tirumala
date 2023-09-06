<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_client_payment".
 *
 * @property integer $client_payment_code
 * @property string $payment_due_date
 * @property integer $payment_done
 * @property string $payment_date
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblClientPayment extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_client_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['payment_due_date', 'payment_date', 'created_at', 'updated_at', 'allow_till_date'], 'safe'],
                [['payment_done'], 'safe'],
                [['created_by', 'updated_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'client_payment_code' => Yii::t('app', 'Client Payment Code'),
            'payment_due_date' => Yii::t('app', 'Payment Due Date'),
            'payment_done' => Yii::t('app', 'Payment Done'),
            'payment_date' => Yii::t('app', 'Payment Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getOverDuePayment($union_list) {
        $date = date('Y-m-d');
        $dataCount = $this->find()
                ->where(['<', 'allow_till_date', $date])
                ->andWhere(['or', ['payment_done' => 0], ['is', 'payment_done', NULL]])
                ->andWhere(['in','union_code',$union_list])
                ->orderBy('allow_till_date desc')
                ->one();
        return $dataCount;
    }

    public function getPendingPaymentCount($union_list) {
        $date = date('Y-m-d');
        $dataCount = $this->find()
                ->where(['<=', 'payment_due_date', $date])
                ->andWhere(['or', ['payment_done' => 0], ['is', 'payment_done', NULL]])
                 ->andWhere(['in','union_code',$union_list])
                ->orderBy('payment_due_date desc')
                ->one();
        return $dataCount;
    }

}
