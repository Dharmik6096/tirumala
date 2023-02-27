<?php

namespace app\modules\general\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\general\models\TblApprovalStagesProcess;

/**
 * This is the model class for table "tbl_approval_stages".
 *
 * @property integer $approval_stages_code
 * @property string $union_code
 * @property string $process_name
 * @property string $approval_mode
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblApprovalStages extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_approval_stages';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['approval_stages_code'], 'required'],
            [['union_code', 'process_name', 'approval_mode', 'remarks'], 'safe'],
            [['created_at', 'updated_at', 'created_by', 'updated_by', 'originating_type', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['approval_stages_code'], 'integer'],
            [['process_name'], 'validateProcess'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'approval_stages_code' => Yii::t('app', 'Approval Stages Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'process_name' => Yii::t('app', 'Process Name'),
            'approval_mode' => Yii::t('app', 'Approval Mode'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getProcessCode() {
        return $this->hasOne(TblApprovalStagesProcess::className(), ['process_name' => 'process_name']);
    }

    public function validateProcess($attribute, $params) {
        $existData = $this->find()->where(['process_name' => $this->process_name])
                ->andWhere(['!=', 'approval_stages_code', $this->approval_stages_code])
                ->one();
        if ((!empty($existData))) {
            $this->addError('process_name', Yii::t('app/validation', 'Approval Statges is Already Exist for selected Process.'));
        }
    }

}
