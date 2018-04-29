<?php

namespace app\modules\complaint\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "tbl_complaint".
 *
 * @property integer $complaint_code
 * @property string $union_code
 * @property string $dcs_code
 * @property string $date
 * @property string $remarks
 * @property string $complaint_type
 * @property string $status
 * @property string $resolve_date
 * @property string $resolve_remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $delete_at
 * @property string $delete_by
 */
class TblComplaint extends \app\models\ChildModel {

    public $old_attachment;
//    public $contact_person;
//    public $issue_type;
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_complaint';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['contact_person', 'union_code', 'dcs_code', 'remarks', 'complaint_type', 'status'], 'required'],
            [['union_code', 'dcs_code', 'remarks', 'complaint_type', 'status', 'created_by', 'updated_by'], 'string'],
            [['date', 'created_at', 'updated_at', 'affects_data', 'attachment'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'complaint_code' => Yii::t('app', 'Complaint Code'),
            'union_code' => Yii::t('app', 'Union'),
            'dcs_code' => Yii::t('app', 'Society Name'),
            'date' => Yii::t('app', 'Date'),
            'remarks' => Yii::t('app', 'Remarks'),
            'contact_person' => Yii::t('app', 'Contact Person'),
            'complaint_type' => Yii::t('app', 'Complaint Type'),
            'affects_data' => Yii::t('app', 'Affects Data'),
            'attachment' => Yii::t('app', 'Attachment'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getComplaintStatus($id) {
        return $this->find()->select('status')->where(['status' => 'Resolved', 'complaint_code' => $id])->one();
    }

    public function getCode() {
        $data = $this->find()->select(["MAX(CONVERT(INT,complaint_code)) AS complaint_code"])->one();
        return (int) $data['complaint_code'] + 1;
    }

    public function uploadFile($attachment) {
        if (isset($attachment)) {
            // store the source file name
            $this->attachment = $attachment->name;
            $ext = (explode(".", $attachment->name));
            // generate a unique file name
            $files = \yii\helpers\FileHelper::findFiles(Yii::$app->params['complaint_dir_path'], ['only' => ['*.' . $ext[1]]]);
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

    /**
     * @inheritdoc
     * @return TblComplaintQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblComplaintQuery(get_called_class());
    }

}
