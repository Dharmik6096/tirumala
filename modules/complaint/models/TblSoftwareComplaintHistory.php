<?php

namespace app\modules\complaint\models;

use Yii;

/**
 * This is the model class for table "tbl_software_complaint_history".
 *
 * @property integer $id
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
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblSoftwareComplaintHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_software_complaint_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['complaint_date', 'assign_date', 'resolve_date', 'created_at', 'updated_at', 'history_created_at', 'complaint_desc', 'remarks', 'attachment', 'km', 'is_chargeable', 'originating_type', 'amount', 'complaint_code', 'service_call_no', 'assign_time', 'resolution_type', 'complaint_status', 'union_code', 'dcs_code', 'contact_person', 'contact_person_no', 'product_code', 'product_name', 'complaint_type', 'priority', 'assign_to', 'created_by', 'updated_by', 'history_created_by', 'originating_org_code', 'originating_org_type', 'operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'complaint_code' => Yii::t('app', 'Complaint Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
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
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
