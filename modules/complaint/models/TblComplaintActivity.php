<?php

namespace app\modules\complaint\models;

use Yii;
use app\modules\complaint\models\TblComplaint;

/**
 * This is the model class for table "tbl_complain_activity".
 *
 * @property integer $id
 * @property integer $complain_code
 * @property string $status
 * @property string $remarks
 * @property string $issue_type
 * @property string $contact_person
 * @property string $date
 * @property string $updated_at
 * @property string $updated_by
 */
class TblComplaintActivity extends \app\models\ChildModel {

    public $old_attachment;
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_complaint_activity';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['contact_person', 'status', 'remarks', 'issue_type'], 'required'],
//            [['id'], 'integer'],
            [['status', 'remarks', 'issue_type', 'contact_person', 'updated_by'], 'string'],
            [['date', 'updated_at', 'affects_data', 'attachment'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'complaint_code' => Yii::t('app', 'Complain Code'),
            'status' => Yii::t('app', 'Status'),
            'remarks' => Yii::t('app', 'Remarks'),
            'issue_type' => Yii::t('app', 'Complaint Type'),
            'contact_person' => Yii::t('app', 'Contact Person'),
            'affects_data' => Yii::t('app', 'Affects Data'),
            'attachment' => Yii::t('app', 'Attachment'),
            'date' => Yii::t('app', 'Date'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getComplaintCode() {
        return $this->hasOne(TblComplaint::className(), ['complaint_code' => 'complaint_code']);
    }

    public function getType() {
        $data = $this->find()->select('issue_type')->where(['complaint_code' => $this->complaint_code])->orderBy(['updated_at' => SORT_DESC])->one();
        return $data['issue_type'];
    }

    /**
     * @inheritdoc
     * @return TblComplainActivityQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblComplaintActivityQuery(get_called_class());
    }

}
