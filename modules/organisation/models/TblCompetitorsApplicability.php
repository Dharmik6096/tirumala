<?php

namespace app\modules\organisation\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_competitors_applicability".
 *
 * @property integer $competitors_applicability_id
 * @property integer $competitor_id
 * @property string $customer_type
 * @property string $customer_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblCompetitorsApplicability extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_competitors_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['competitor_id', 'originating_type', 'created_by', 'updated_by', 'created_at', 'updated_at', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'customer_type', 'customer_code', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'competitors_applicability_id' => Yii::t('app', 'Competitors Applicability ID'),
            'competitor_id' => Yii::t('app', 'Competitor ID'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getExistData() {
        return $this->find()->andWhere(['competitor_id' => $this->competitor_id, 'union_code' => $this->union_code, 'plant_code' => $this->plant_code, 'mcc_plant_code' => $this->mcc_plant_code, 'bmc_code' => $this->bmc_code])->all();
    }

}
