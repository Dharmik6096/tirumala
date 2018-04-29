<?php

namespace app\modules\restservices\models;

use ReflectionClass;
use app\models\GeneralModel;
use app\modules\collection\models\TblSendSms;
use app\modules\report\models\TblMilkCollection;
use app\modules\restservices\models\RestModel;
use app\components\SmsApi;

class ReilModel extends RestModel {
    
    private $token='cGNkZkBAMTIzJDk4Nw';
    
    private $fieldList=['society_code','member_code','fat','snf','qty','rate','amount','date','shift'];



    public function getCollectionData($data)
    {
        if(empty($data['token']) || $data['token']!=$this->token)
        {
            return 'Invalid Token!';
        }
        
        $recFields=  array_keys($data);
        $diff=  array_diff($this->fieldList, $recFields);
        if($diff)
        {
            return 'Some fields are missing!';
        }
        switch ($data['shift'])
        {
            case 'all':
                $shift=3;
                break;
            case 'morning':
                $shift=1;
                break;
            case 'evening':
                $shift=2;
                break;
            default :
                $shift='0';
        }
            $smsModel=new TblSendSms();
            
            $smsModel->dcs_code=$data['society_code'];
            $smsModel->member_code=$data['member_code'];
            $smsModel->fat=$data['fat'];
            $smsModel->snf=$data['snf'];
            $smsModel->qty=$data['qty'];
            $smsModel->rate=$data['rate'];
            $smsModel->amount=$data['amount'];
            $smsModel->date_time_of_collection=$data['date'];
            $smsModel->shift=$shift;
            $smsModel->status='inquiry';
            $smsModel->save(false);
            
        $collection=new TblMilkCollection();
        $collection= $collection->find()->where([
            'tbl_milk_collection.dcs_code'=>$data['society_code'],
            'tbl_milk_collection.member_code'=>$data['member_code'],
            'fat'=>$data['fat'],
            'snf'=>$data['snf'],
            'qty'=>$data['qty'],
            'rtpl'=>$data['rate'],
            'amount'=>$data['amount'],
            'CAST(date_time_of_collection AS DATE)'=>$data['date'],
            'tbl_milk_collection.shift'=>$shift,
        ])->joinWith(['memberCode','dcsCode','shiftCode','milkTypeCode'])->one();

        if(!empty($collection))
        {
            $smsModel->status='found';
            $smsModel->save(false);
            $send=new SmsApi();
            if(!empty($collection->memberCode->mobile_no))
            {
                $msg="Dear ".$collection->memberCode->member_name.",\n";
                $msg.="Collection Received\n";
                $msg.="S.CODE:".$collection->dcs_code."\n";
                $msg.="DATE:".date('d-m-Y',  strtotime($collection->date_time_of_collection))."\n";
                $msg.="SHIFT:".$collection->shiftCode->shift."\n";
                $msg.="M.CODE:".$collection->member_code."\n";
                $msg.="TYPE:".$collection->milkTypeCode->animal_type_name."\n";
                $msg.="FAT:".$collection->fat."\n";
                $msg.="SNF:".$collection->snf."\n";
                $msg.="QTY:".$collection->qty."\n";
                $msg.="AMT:".$collection->amount."\n";
                
                $sent=$send->sendsmsPOST($collection->memberCode->mobile_no,$msg);
                $sent=json_decode($sent);
                if($sent->responseCode=='3001')
                {
                    $smsModel->status='sms-sent';                    
                }
                else {
                    $smsModel->status='sms-failed';
                }
                $smsModel->mobile=$collection->memberCode->mobile_no;
                $smsModel->save(false);
            }
            
            return 'Data received successfully!';
        }
        else
        {
            return 'Data not found!';
        }        
    }
}
