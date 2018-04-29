<?php
namespace app\modules\changelog\components;

use yii\base\Widget;
use yii\helpers\Html;
use Yii;
use app\modules\changelog\models\TblChangeLog;
use yii\helpers\Url;

/*
 * Description: This widget used to display last change log update
 * By: Harita
 * Date: 31-10-2017
 */

class LastUpdateWidget extends Widget{

    public $title="Last Updated On:";
    public $dtformat='d-m-Y H:i:s';
    public $withLink=true;

    public function init(){
            $query= TblChangeLog::find()->select(['created_at'])->orderBy(['created_at'=>SORT_DESC])->one();
            $datetime=!empty($query)?$query->created_at:'';
            if(!empty($datetime))
            {
                $msg=$this->title.' '.date($this->dtformat,  strtotime($datetime));
                if($this->withLink)
                    echo Html::a($msg,  Url::to(['/changelog/tbl-change-log/index']));
                else
                    echo $msg;
            }
    }
    
}
?>
