<?php

namespace app\modules\veterinary\models;

use app\models\ChildModel;
use app\modules\organisation\models\TblUnions;
use Yii;

/**
 * This is the model class for table "tbl_case_type_fees".
 *
 * @property integer $case_type_fee_id
 * @property integer $case_type_id
 * @property string $amount
 * @property string $wef_date
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblCaseTypeFees extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_case_type_fees';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['case_type_id', 'originating_type'], 'integer'],
            [['amount'], 'number'],
            [['case_type_id', 'amount', 'wef_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'union_code'], 'safe'],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['wef_date'], 'unique', 'targetAttribute' => ['union_code', 'wef_date'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'case_type_fee_id' => Yii::t('app', 'Case Type Fee ID'),
            'case_type_id' => Yii::t('app', 'Case Type Name'),
            'amount' => Yii::t('app', 'Amount'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'union_code' => Yii::t('app', 'Union'),
        ];
    }

    public function getCaseType() {
        return $this->hasOne(TblCaseType::className(), ['case_type_id' => 'case_type_id']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

}
