<?php

namespace app\modules\email\controllers;

use Yii;
use yii\web\Controller;
use yii\swiftmailer\Mailer;
use app\modules\email\models\TblEmailRuleMaster;
use app\modules\email\models\TblEmailLog;
use app\modules\email\models\TblEmailLogHistory;
use yii\filters\VerbFilter;
/**
 * Default controller for the `email` module
 */
class DefaultController extends Controller
{
     public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionReadRuleMaster(){
        $email_rule_model = new TblEmailRuleMaster();
        $email_rule_info = $email_rule_model->getEmailRules();
        foreach($email_rule_info as $rules){
            $current_date = date('Y-m-d');
            
            $log_model = new TblEmailLog();
            $log_data = $log_model->find()->select('to_date')->where(['email_rule_master_id' => $rules->email_rule_master_id])->orderBy('email_log_id desc')->one();
            if(isset($log_data->to_date) && $log_data->to_date != ''){          
                $from_date = $log_data->to_date;
            }else{
                $from_date = '2018-01-01';
            }
            $to_date = date('Y-m-d', strtotime('-'.$rules->interval.' day', strtotime($current_date)));
            
            $result = \Yii::$app->db->createCommand("{CALL ".$rules->emailProcess->sp_name."(:union_code,:from_date,:to_date)}")
                ->bindValue(':union_code', $rules->union_code)
                ->bindValue(':from_date', $from_date)
                ->bindValue(':to_date', $to_date);
            $query = $result->queryAll();
            
            if(!empty($query)){
                $heads = array_keys($query[0]);
                $body = '';
                $body .= "<table style='background:#eee;padding:4px;'>";
                $body .= "<tr align='left' style='margin:0;padding:0;'>";
                foreach($heads as $key=>$val){
                    $body .= "<th style='padding:4px 8px;color:#111;border-bottom:1px solid #000;margin:0;border-top:1px solid #000;margin:0px'>".ucwords($val)."</th>";
                }
                $body .= "</tr>";
                foreach($query as $data){
                    $body .= "<tr style='margin:0:padding:0'>";
                    foreach($data as $head=>$value){
                        $body .= "<td style='padding:4px 8px;color:#333;border-bottom:1px solid #000;margin:0'>".$value."</td>";
                    }
                    $body .= "</tr>";
                }
                $body .= "</table>";

                $mail_body = $rules->email_body.'<br/><br/>'.$body;
                $subject = "Reminder - 1: ".$rules->email_subject." ". date('d-m-Y', strtotime($to_date));
               
                $current_date = date('Y-m-d H:i:s');

                $to_mail = $rules->email;
                $email = $email_rule_model->sendEmail($subject,$mail_body,$to_mail);
                $mobile = "91".$rules->mobile;
                $message = $rules->message." ".date('d-m-Y', strtotime($to_date));
                $send = Yii::$app->bsmartsms->sendSmsPOST($mobile, $message);
                
                $next_time = date("Y-m-d H:i:s", strtotime('+'.$rules->frequency. 'hours'));
                $data = $rules->attributes;
                $log_model->setAttributes($data);
                $log_model->to_date = $to_date;
                $log_model->from_date = $from_date;
                $log_model->current_datetime = date('Y-m-d H:i:s');
                $log_model->next_datetime = $next_time;
                $log_model->is_processed = 0;
                $log_model->sent_count = 1;
                $log_model->total_count = $rules->no_of_email;
                $transaction = $this->generalModel->saveTransaction([$log_model], ['Email Rule Mastr', 'create']);
            }
            
        }
        
    }
    
    public function actionReadEmailLog(){
        $email_rule_model = new TblEmailLog();
        $log_data = $email_rule_model->emailData();
        foreach($log_data as $rules){
            $historyModel = new TblEmailLogHistory();
            Yii::$app->operation->history($rules, $historyModel, UPDATE);
            
            $current_date = date('Y-m-d');
            $from_date = $rules->from_date;
            $to_date = $rules->to_date;
            $sp_name = $rules->emailRuleMaster->emailProcess->sp_name;
            
            $result = \Yii::$app->db->createCommand("{CALL ".$sp_name."(:union_code,:from_date,:to_date)}")
                ->bindValue(':union_code', $rules->union_code)
                ->bindValue(':from_date', $from_date)
                ->bindValue(':to_date', $to_date);
            $query = $result->queryAll();
            if(empty($query)){
                $rules->is_processed = 1;
            }else{
                $heads = array_keys($query[0]);
                $body = '';
                $body .= "<table style='background:#eee;padding:4px;'>";
                $body .= "<tr align='left' style='margin:0;padding:0;'>";
                foreach($heads as $key=>$val){
                    $body .= "<th style='padding:4px 8px;color:#111;border-bottom:1px solid #000;margin:0;border-top:1px solid #000;margin:0px'>".ucwords($val)."</th>";
                }
                $body .= "</tr>";
                foreach($query as $data){
                    $body .= "<tr style='margin:0:padding:0'>";
                    foreach($data as $head=>$value){
                        $body .= "<td style='padding:4px 8px;color:#333;border-bottom:1px solid #000;margin:0'>".$value."</td>";
                    }
                    $body .= "</tr>";
                }
                $body .= "</table>";
                

                $mail_body = $rules->emailRuleMaster->email_body.'<br/><br/>'.$body;
                $subject = 'Reminder-'.($rules->sent_count + 1).": ".$rules->emailRuleMaster->email_subject.' '.date('d-m-Y', strtotime($to_date));

                $email_rule_model = new TblEmailRuleMaster();
                $to_mail = $rules->emailRuleMaster->email;
                $email = $email_rule_model->sendEmail($subject,$mail_body,$to_mail);
                $mobile = "91".$rules->emailRuleMaster->mobile;
                $message = str_replace('[DATE]', date('d-m-Y', strtotime($to_date)), $rules->emailRuleMaster->message);
                $send = Yii::$app->bsmartsms->sendSmsPOST($mobile, $message);
                
                $next_time = date("Y-m-d H:i:s", strtotime('+'.$rules->frequency. 'hours'));
                $new_count = $rules->sent_count + 1;
                $rules->current_datetime = date('Y-m-d h:i:s');
                $rules->next_datetime = $next_time;
                $rules->sent_count = $new_count;
                if($new_count == $rules->total_count){
                    $rules->is_processed = 1;
                }else{
                    $rules->is_processed = 0;
                }
            }
            
//            $transaction = $this->generalModel->saveTransaction([$rules,$historyModel], ['Email Log', 'edit']);
            
        }
        
    }
}
