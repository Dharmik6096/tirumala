<?php

namespace app\modules\email\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use yii\validators\EmailValidator;
/**
 * This is the model class for table "tbl_email_rule_master".
 *
 * @property integer $email_rule_master_id
 * @property integer $rule_id
 * @property integer $frequency
 * @property integer $interval
 * @property integer $no_of_email
 * @property string $email
 * @property string $mobile
 * @property string $message
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $is_active
 */
class TblEmailRuleMaster extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_email_rule_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['union_code', 'rule_id', 'frequency', 'interval', 'no_of_email', 'message', 'email', 'mobile','email_subject','email_body'], 'required'],
            [['mobile'], 'integer'],
            [['email'], 'email'],
            [['rule_id', 'frequency', 'interval', 'no_of_email', 'is_active'], 'integer'],
            [['email', 'mobile', 'message', 'union_code', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email_rule_master_id' => Yii::t('app', 'Email Rule Master ID'),
            'rule_id' => Yii::t('app', 'Rule'),
            'frequency' => Yii::t('app', 'Frequency'),
            'interval' => Yii::t('app', 'Interval'),
            'no_of_email' => Yii::t('app', 'No Of Email'),
            'email' => Yii::t('app', 'Email'),
            'mobile' => Yii::t('app', 'Mobile'),
            'email_subject' => Yii::t('app', 'Email Subject'),
            'email_body' => Yii::t('app', 'Email Body'),
            'message' => Yii::t('app', 'Message'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }
        
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }
    
    
    public function getEmailProcess() {
        return $this->hasOne(TblEmailProcessMaster::className(), ['rule_id' => 'rule_id']);
    }
    
    public function getEmailRules(){
        return $this->find()->select(['tbl_email_rule_master.*'])->joinWith(['emailProcess'])->where(['tbl_email_rule_master.is_active'=>1,'tbl_email_process_master.is_active'=>1])->all();
    }
    
    public function sendEmail($subject,$body,$to_mail){
		try {
        	$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';
			$headers[] = 'From: PCDF ESCALATION <no-reply@portal.pcdf-eipl.com>';
			mail($to_mail, $subject, $body,implode("\r\n", $headers));
        return true;
		}
		catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
	 return true;
}
    }
    
    
    public function checkEmailList($attribute, $params) {
        $emails = $this->email;
        //convert email list to string to an array so we can loop through it using ";"  as a delimiter.
        //if it is in an array do nothing i.e. using somthing like select2
        $emails = explode(',', $emails);
        if (is_array($emails)) {
                $emails = explode(',', $emails);
        }
        //declair email validator
        $validator = new EmailValidator;
        foreach ($emails as $email) {
                if (!$validator->validate($email)) {
                $this->addError($attribute, "'.$email.' is not a valid email.");
                }
        }
    }
    
    
}
