<?php

namespace app\modules\insurance\models;

use app\models\ChildModel;
use app\modules\syncutility\models\TblSentbox;
use Yii;
use yii\base\UserException;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_insurance_detail_summary".
 *
 * @property integer $insurance_detail_summary_code
 * @property integer $insurance_master_code
 * @property string $dcs_code
 * @property string $from_date
 * @property string $to_date
 * @property string $status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblInsuranceDetailSummary extends ChildModel {

    public $is_sentbox = TRUE;
    public $operation, $is_revoke;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_insurance_detail_summary';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['insurance_master_code', 'dcs_code', 'from_date', 'to_date', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_name', 'is_revoke'], 'safe'],
            [['insurance_master_code', 'dcs_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'to_date', 'from_date'], 'required', 'on' => ['extend_date']],
            [['dcs_code'], 'unique', 'targetAttribute' => ['dcs_code', 'insurance_master_code'], 'message' => Yii::t('app/validation', 'The combination of {attribute} And Insurance Master Code has already been taken.'), 'except' => ['extend_date', 'publish_finalize_summary']],
            [['insurance_master_code'], 'checkDetail', 'on' => ['extend_date']],
            [['to_date'], 'convertDate', 'on' => ['extend_date']],
            [['to_date'], 'validateToDate', 'on' => ['extend_date']],
            [['insurance_master_code'], 'safe', 'on' => ['androidsync']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'insurance_detail_summary_code' => Yii::t('app', 'Insurance Detail Summary Code'),
            'insurance_master_code' => Yii::t('app', 'Insurance Master'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function checkDetail($attribute, $param) {
        $result = $this->find()->where(['insurance_master_code' => $this->insurance_master_code, 'dcs_code' => $this->dcs_code])->one();
        if (empty($result)) {
            $this->addError('insurance_master_code', 'Selected Society insurance record not avaliable.');
            return false;
        } else if ($result->status == 'FINALIZE') {
            $this->addError('insurance_master_code', 'You cannot extend date for this record because it has been finalized.');
            return false;
        }
        return true;
    }

    public function convertDate($attribute, $param) {
        if (empty($this->getErrors())) {
            $this->{$attribute} = !empty($this->{$attribute}) ? Yii::$app->controls->view_date($this->{$attribute}, 'php:Y-m-d') : NULL;
        }
    }

    public function getInsuranceDetailSummary($insurance_master_code, $dcs_code = '', $status = '') {
        $result = $this->find()->where(['insurance_master_code' => $insurance_master_code]);
        if (!empty($dcs_code)) {
            $result = $result->andWhere(['dcs_code' => $dcs_code]);
        }
        if (!empty($status)) {
            $result = $result->andWhere(['status' => $status]);
        }
        return $result->one();
    }

    public function validateToDate($attribute, $param) {
        $result = $this->find()->where(['insurance_master_code' => $this->insurance_master_code, 'dcs_code' => $this->dcs_code])->one();
        if (!empty($result)) {
            if (strtotime($this->to_date) <= strtotime($result->from_date)) {
                $this->addError($attribute, 'Insurance to date must be greater than ' . $this->from_date . '.');
                return false;
            }
        }
    }

    public function getPublishFinalizeData($insurance_master_code, $status) {
        $status = (strtolower($status) == 'publish') ? 'DRAFT' : 'PUBLISH';
        return $this->find()->where(['insurance_master_code' => $insurance_master_code, 'status' => $status])->all();
    }

    public function getBMCDCSList($insuranceCode, $bmcCode, $date) {
        $status = ['PUBLISH', 'PARTIAL_FINALIZE'];
        $query = $this->find()->select(['dcs_code', 'dcs_name'])
                ->where(['insurance_master_code' => $insuranceCode, 'bmc_code' => $bmcCode, 'status' => $status]);

        if ($date) {
            $today = date("Y-m-d");
            $query->andWhere(['<', 'to_date', $today]);
        }

        $results = $query->all();

        $value = ArrayHelper::map($results, 'dcs_code', function ($value) {
                    return $value->dcs_name . ' - ' . $value->dcs_code;
                });
        return $value;
    }

    public function afterSave($insert, $changedAttributes) {
        if (strtolower($this->status) == 'publish' || strtolower($this->status) == 'partial_finalize') {
            $sentboxArray = [];
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->dcs_code);
            foreach ($sentboxArray as $sent) {
                $flag = ((isset($this->operation) && $this->operation == true) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';
                $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
                if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                    if (!($sentbox->setSentbox($this, $flag))) {
                        throw new UserException("SentBox Entry is not created so transaction is rollback!");
                    }
                }
            }
        }
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

}
