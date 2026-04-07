<?php

namespace app\modules\complaint\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcs;
use webvimark\modules\UserManagement\models\User;
use app\modules\usermanagement\models\TblUserEngineerMapping;

/**
 * This is the model class for table "tbl_software_complaint".
 *
 * @property string $complaint_code
 * @property string $union_code
 * @property string $dcs_code
 * @property string $contact_person
 * @property string $contact_person_no
 * @property string $complaint_date
 * @property string $product_code
 * @property string $product_name
 * @property string $service_call_no
 * @property string $complaint_type
 * @property string $priority
 * @property string $complaint_desc
 * @property string $remarks
 * @property string $assign_to
 * @property string $assign_date
 * @property string $assign_time
 * @property string $resolution_type
 * @property string $resolve_date
 * @property integer $is_chargeable
 * @property string $amount
 * @property string $complaint_status
 * @property string $attachment
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblSoftwareComplaint extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_software_complaint';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['complaint_code', 'union_code', 'dcs_code', 'contact_person', 'contact_person_no', 'complaint_date', 'complaint_type', 'priority', 'complaint_desc', 'location', 'km', 'complaint_date', 'assign_date', 'resolve_date', 'created_at', 'updated_at', 'complaint_desc', 'remarks', 'attachment', 'is_chargeable', 'originating_type', 'complaint_code', 'service_call_no', 'assign_time', 'resolution_type', 'complaint_status', 'union_code', 'dcs_code', 'contact_person', 'contact_person_no', 'product_code', 'product_name', 'complaint_type', 'priority', 'assign_to', 'created_by', 'updated_by', 'is_chargeable', 'originating_org_type', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['complaint_code', 'union_code', 'dcs_code', 'contact_person', 'contact_person_no', 'complaint_date', 'complaint_type', 'priority', 'complaint_desc'], 'required', 'on' => ['create']],
                [['assign_to'], 'required', 'on' => ['createdUser']],
                [['resolve_date', 'resolution_type'], 'required', 'on' => ['resolve', 'createdUser']],
                [['amount'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return $model->is_chargeable == '1';
                }, 'whenClient' => "function (attribute, value) { 
                    return $('#tblsoftwarecomplaint-is_chargeable').is(':checked'); 
                }", 'on' => ['resolve', 'createdUser']],
                [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'complaint_code' => Yii::t('app', 'Complaint Code'),
            'union_code' => Yii::t('app', 'Union'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'contact_person' => Yii::t('app', 'Contact Person'),
            'contact_person_no' => Yii::t('app', 'Contact Person No'),
            'complaint_date' => Yii::t('app', 'Complaint Date'),
            'product_code' => Yii::t('app', 'Product Code'),
            'product_name' => Yii::t('app', 'Product Name'),
            'service_call_no' => Yii::t('app', 'Service Call No'),
            'complaint_type' => Yii::t('app', 'Complaint Type'),
            'priority' => Yii::t('app', 'Priority'),
            'complaint_desc' => Yii::t('app', 'Complaint Desc'),
            'remarks' => Yii::t('app', 'Remarks'),
            'assign_to' => Yii::t('app', 'Assign To'),
            'assign_date' => Yii::t('app', 'Assign Date'),
            'assign_time' => Yii::t('app', 'Assign Time'),
            'resolution_type' => Yii::t('app', 'Resolution Type'),
            'resolve_date' => Yii::t('app', 'Resolve Date'),
            'is_chargeable' => Yii::t('app', 'Is Chargeable'),
            'amount' => Yii::t('app', 'Amount'),
            'complaint_status' => Yii::t('app', 'Complaint Status'),
            'attachment' => Yii::t('app', 'Attachment'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'km' => Yii::t('app', 'Km'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function uploadFile($attachment) {
        if (isset($attachment)) {
            // store the source file name
            $this->attachment = str_replace(' ', '', $attachment->name);
            $ext = (explode(".", $attachment->name));
            // generate a unique file name
            $files = \yii\helpers\FileHelper::findFiles(Yii::$app->params['software_complaint_dir_path'], ['only' => ['*.' . $ext[1]]]);
            if (isset($files[0])) {
                foreach ($files as $index => $file) {
                    $fileName = substr($file, strrpos($file, '/') + 2);
                    if ($this->attachment == $fileName) {
                        $fn = explode('.', $fileName);
                        $fn = $fn[0] . '(' . ($index + 1) . ').' . $fn[1];
                    }
                }
                return isset($fn) ? $fn : $this->attachment;
            }
            return $this->attachment;
        }
    }

    public function getAssignTo() {
        return $this->hasOne(User::className(), ['id' => 'assign_to']);
    }

    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getServiceCall($model, $autoInc = 1) {
        $primaryKey = 'service_call_no';
        $date = date('dmy');
        $defaultCode = 'O' . $date . '/';
        $len = strlen($defaultCode);
        $val = $model->find()
                ->select(["MAX(CONVERT(INT,substring(" . $primaryKey . ", " . $len . " +1,4))) AS " . $primaryKey])
                ->where("SUBSTRING(" . $primaryKey . ", 1," . $len . ")='" . trim($defaultCode) . "'")
                ->one();
        $code1 = (int) $val[$primaryKey] + $autoInc;
        $value = $defaultCode . $code1;

        return $value;
    }

    public function getEngineerMap() {
        return $this->hasMany(TblUserEngineerMapping::className(), ['engineer_id' => 'assign_to']);
    }

}
