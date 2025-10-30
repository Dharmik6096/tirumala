<?php

namespace app\components;

use Yii;
use SoapClient;
use stdClass;
use linslin\yii2\curl\Curl;
use GuzzleHttp;
use GuzzleHttp\Psr7;
use app\modules\sms\models\TblApiDetail;

class AlertNotification {

    public function sendSms($id, $mob_no, $msg, $temp_id = '', &$status = 2) {
        $mob_no = (!empty($mob_no) && Yii::$app->general->decryptData($mob_no) !== FALSE) ? Yii::$app->general->decryptData($mob_no) : $mob_no;
        $api = (new TblApiDetail())->getApi($id); //get all api parameters to send sms
        if (!empty($api)) {
            $request_param = $this->requestData($api[0]['apiMaster']['api_method'], $api[0]['apiMaster']['api_category']);  //get method & param for request           
            $url = $api[0]['apiMaster']['url']; //url
            foreach ($api as $a) {
                //if {mobileno} found in value then replace it with actual no 
                //if {msg} found in value then replace it with actual text message     
                $value = (strpos($a['key_value'], '{mobileno}') !== false) ? str_replace('{mobileno}', $mob_no, $a['key_value']) : (($a['key_value'] == '{msg}') ? $msg : (($a['key_value'] == '{templateid}') ? $temp_id : $a['key_value']));
                $value = ($a['key_value'] == '{timeStamp}') ? date('dmYHms') : $value;
                if (!empty($a['url_append'])) {
                    $url = $url.$a['url_append'].$value;
                } elseif (!empty($a['header_flag']) && $a['header_flag'] == 1) {
                    $headers[$a['parameter_key']] = $value; // Add to headers
                } elseif (!empty($a['parent_tag'])) {
                    if (!empty($a['parent_type']) && $a['parent_type'] == 'string') {
                        $param[$a['parent_tag']][$a['parameter_key']] = $value; //set parent key to key as single
                    } else {
                        $param[$a['parent_tag']][0][$a['parameter_key']] = $value; //set parent key to key as array
                    }
                } else {
                    $param[$a['parameter_key']] = $value;  //assign value to key and generate dynemic array
                }
            }
            $client = new GuzzleHttp\Client();
            $urlCheck = explode('/', $url);
            $checkParam = $param;
            $param = [];
            foreach ($checkParam as $k => $checkP) {
                if ($k == 'replace_keys') {
                    $dat = explode('&', $checkP);
                    foreach ($dat as $var) {
                        $v = explode('##', $var);
                        if (!empty($v[0])) {
                            $param[$v[0]] = !empty($v[1]) ? $v[1] : '';
                        }
                    }
                } else {
                    $param[$k] = $checkP;
                }
            }
            try {
                foreach ($param as $p) {
                    if (is_string($p) && strpos($p, "%")) {
                        $param = urldecode(http_build_query($param));
                        break;
                    }
                }
                $option_array=[];
                $option_array[$request_param['param']]=  $param;
                if(!empty($headers)){
                    $option_array['headers']=$headers;
                }
                $response = $client->request($request_param['method'], $url, $option_array);   
                $data = $response->getBody(); //get response . guzzle return respone in stream object
                $stream = Psr7\Utils::streamFor($data); //convert stream response to string
                return json_encode($stream->getContents()); //getreponse in string format
            } catch (GuzzleHttp\Exception\RequestException $ex) {
                $status = 3;
                return json_encode($ex->getMessage());
            }
        }
    }

    /*
     * set method and parameter key to send request
     * http:
     * if method 'Get'
     *      method=get,param='query'
     * if method 'post'
     *      method=post,param=form_params
     * if type='json'
     *      method=post,param=json
     * 
     * for ref : http://docs.guzzlephp.org/en/latest/request-options.html
     */

    private function requestData($method, $type) {
        $param = [];
        $method = strtolower($method);
        $type = strtolower($type);
        switch ($type) {
            case 'http' : $param['method'] = $method;
                $param['param'] = ($method == 'get') ? 'query' : 'form_params';
                break;
            case 'json' : $param['method'] = 'post';
                $param['param'] = 'json';
                break;
        }
        return $param;
    }

    public function sendNotification($url, $serverKey, $id, $header, $message, $parent_code) {
        $response = 'Error';
        if (!empty($url) && !empty($serverKey)) {
            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: key=' . $serverKey;
            $reg_id = [];
            $reg_id[] = $id;
            if (!empty($reg_id)) {
                $notification = array('title' => $header, 'body' => $message, 'code' => $parent_code, 'sound' => 'default', 'badge' => '1');
                $arrayToSend = array('registration_ids' => $reg_id, 'data' => $notification, 'priority' => 'high');
                $json = json_encode($arrayToSend);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//Send the request
                $response = curl_exec($ch);
//Close request
                if ($response === FALSE) {
                    die('FCM Send Error: ' . curl_error($ch));
                }
                curl_close($ch);
            }
        }
        return $response;
    }

    public function sendEmail($from, $to_mail, $cc, $subject, $body, $attachment = FALSE, $filename = '', $filepath = '', $bcc = '', $pwd = '') {
        try {
            $to_mail = explode(',', $to_mail);
            $to_mail = array_filter($to_mail, function ($s) {
                return filter_var($s, FILTER_VALIDATE_EMAIL);
            });
            $mailer = Yii::$app->mailer;
            if (!empty($pwd)) {
                $reflectionMailer = new \ReflectionObject($mailer);
                $transportProperty = $reflectionMailer->getProperty('_transport');
                $transportProperty->setAccessible(true);
                $transport = $transportProperty->getValue($mailer);
                $transport['password'] = $pwd;
                $mailer->setTransport($transport);
            }
            $email = $mailer->compose()
                    ->setTo($to_mail)
                    ->setFrom($from)
                    ->setSubject($subject)
                    ->setHtmlBody($body);
            if (!empty($cc)) {
                $cc = explode(',', $cc);
                $cc = array_filter($cc, function ($s) {
                    return filter_var($s, FILTER_VALIDATE_EMAIL);
                });
                $email->setCc($cc);
            }
            if (!empty($bcc)) {
                $bcc = explode(',', $bcc);
                $bcc = array_filter($bcc, function ($s) {
                    return filter_var($s, FILTER_VALIDATE_EMAIL);
                });
                $email->setBcc($bcc);
            }
            if (!empty($filepath)) {
                $email->attach(Yii::$app->basePath . $filepath, ['fileName' => $filename]);
            } else if ($attachment) {
                $email->attachContent($attachment, [
                    'fileName' => $filename,
                    'contentType' => 'application/pdf'
                ]);
            }
            $email->send();
            return true;
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
            return true;
        }
    }

}
