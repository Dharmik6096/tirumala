<?php

namespace app\modules\insurance\models;

use app\models\ChildModel;
use app\modules\organisation\models\TblUnions;
use Yii;
use app\modules\syncutility\models\TblSentbox;
use yii\base\UserException;

/**
 * This is the model class for table "tbl_insurance_master".
 *
 * @property integer $insurance_master_code
 * @property string $insurance_start_date
 * @property string $insurance_end_date
 * @property string $dcs_edit_start_date
 * @property string $dcs_edit_end_date
 * @property integer $member_min_age
 * @property integer $member_max_age
 * @property string $insurance_final_date
 * @property string $insurance_description
 * @property integer $is_active
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
class TblInsuranceMaster extends ChildModel {

    public $operation;
    
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_insurance_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'insurance_start_date', 'insurance_end_date', 'dcs_edit_start_date', 'dcs_edit_end_date', 'member_min_age', 'member_max_age', 'insurance_final_date', 'insurance_description', 'is_active', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'status'], 'safe'],
            [['union_code', 'insurance_start_date', 'insurance_end_date', 'insurance_description'], 'required'],
            [['is_active'], 'default', 'value' => 1, 'except' => ['update']],
            [['insurance_description'], 'string', 'max' => 200],
            [['member_min_age', 'member_max_age'], 'integer', 'min' => 1],
            [['member_min_age'], 'checkAge'],
//            [['dcs_edit_start_date', 'dcs_edit_end_date'], 'checkDate'],
            [['insurance_start_date'], 'validateDate'],
            [['insurance_master_code'], 'required', 'on' => ['import_insurance_detail']],
            [['status'], 'default', 'value' => 'DRAFT'],
            // [['insurance_description'], function ($attribute, $params) {
            //     Yii::$app->general->validateAlphaNumber($this, $attribute, $params);
            // }, 'skipOnEmpty' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'insurance_master_code' => Yii::t('app', 'Insurance Master Code'),
            'union_code' => Yii::t('app', 'Union'),
            'insurance_start_date' => Yii::t('app', 'Insurance Start Date'),
            'insurance_end_date' => Yii::t('app', 'Insurance End Date'),
            'dcs_edit_start_date' => Yii::t('app', 'Society Edit Start Date'),
            'dcs_edit_end_date' => Yii::t('app', 'Society Edit End Date'),
            'member_min_age' => Yii::t('app', 'Member Min Age'),
            'member_max_age' => Yii::t('app', 'Member Max Age'),
            'insurance_final_date' => Yii::t('app', 'Insurance Final Date'),
            'insurance_description' => Yii::t('app', 'Insurance Description'),
            'is_active' => Yii::t('app', 'Is Active'),
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

    public function validateDate($attribute, $param) {
        if (strtotime($this->insurance_start_date) > strtotime($this->insurance_end_date)) {
            $this->addError($attribute, 'Insurance start date must be earlier than end date.');
            return false;
        }
        $query = $this->find()->where('((TRY_CONVERT(date,\'' . $this->insurance_start_date . '\')  between insurance_start_date and insurance_end_date) OR (TRY_CONVERT(date,\'' . $this->insurance_end_date . '\') between insurance_start_date  and insurance_end_date) OR (insurance_start_date between TRY_CONVERT(date,\'' . $this->insurance_start_date . '\') and  TRY_CONVERT(date,\'' . $this->insurance_end_date . '\')) OR (insurance_end_date between TRY_CONVERT(date,\'' . $this->insurance_start_date . '\') and TRY_CONVERT(date,\'' . $this->insurance_end_date . '\')))');
        if (!empty($this->insurance_master_code)) {
            $query = $query->andWhere(['!=', 'insurance_master_code', $this->insurance_master_code]);
        }
        $query = $query->one();
        if (!empty($query)) {
            $this->addError($attribute, 'Insurance dates overlap with existing record.');
            return false;
        }
        return true;
    }

    public function checkDate($attribute, $param) {
        if (strtotime($this->insurance_start_date) > strtotime($this->{$attribute}) || strtotime($this->insurance_end_date) < strtotime($this->{$attribute})) {
            $this->addError($attribute, 'Insurance ' . str_replace('_', ' ', $attribute) . ' select between insurance start date and insurance end date.');
            return false;
        }
        return true;
    }

    public function checkAge($attribute, $param) {
        if ($this->member_min_age > $this->member_max_age) {
            $this->addError($attribute, 'Insurance ' . str_replace('_', ' ', $attribute) . ' select greater than insurance member max age.');
            return false;
        }
        return true;
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function afterSave($insert, $changedAttributes) {
        if (strtolower($this->status) == 'publish') {
            $sentboxArray = [];
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->union_code);
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
