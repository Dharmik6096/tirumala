<?php
namespace app\modules\feedback\models;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class MongoGridFs extends \yii\mongodb\file\ActiveRecord {
    
    public static function collectionName() {
        return "fs";
    }
    
    public function attributes() {
        return [
            '_id',
            'filename',
            'uploadDate',
            'length',
            'chunkSize',
            'md5',
            'file',
            'newFileContent'
        ];
    }
}
