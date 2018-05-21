<?php
/*
 *
 */

namespace app\components;

use yii\base\Component;
use Yii;

class GeneralLabels extends Component {

    private function labelList($type1,$type2) {
        $label=array(
            'title'=>array(
                'create'=>'Add New',
                'edit'=>'Update',
                'list'=>'List',
                'view'=>' Detail View'
            ),
            'button'=>array(
                'create'=>'save',
                'edit'=>'update'
            ),
            'message'=>array(
                'create'=>'Successfully Created',
                'edit'=>'Successfully Updated',
                'info'=>'',
            ),

        );
        return $label[$type1][$type2];
    }

     public function title($type,$param=''){
         if($type=='list' || $type=='view')
             return trim(Yii::t('app',ucwords($param)).' '.ucwords($this->labelList('title',$type)));
         else
            return trim(Yii::t('app',ucwords($this->labelList('title',$type)).' '.Yii::t('app',ucwords($param))));
     }

     public function button($type,$param=''){
         return trim(($this->labelList('button',$type).' '.$param));
     }

     public function message($type,$param=''){
         return Yii::t('app',trim(ucwords($param.' '.$this->labelList('message',$type))));
     }


}