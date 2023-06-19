<?php

namespace app\modules\miscellaneous\controllers;

use yii\web\Controller;

/**
 * Default controller for the `miscellaneous` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
   public function actionIndex($l)
    {
        $this->layout = "@app/web/themes/nddb/layouts/dashboardLayout.php";
       // $model=  $this->languageModel($l);
        $searchModel=null;
       // $dataProvider=$model['left_column'];
        if(isset($model['searchModel'])){
            $searchModel =$model['searchModel'];

           // $dataProvider = $searchModel->search(Yii::$app->request->queryParams)->models;

        }
       // $modelLangauge = $this->getLanguages();
        if (Yii::$app->request->post()) {

                $fields=explode(',',$model['data']['fields']);
                if(isset($_POST[$model['data']['post']])){
                $post = $_POST[$model['data']['post']];



                foreach ($post as $row){
                    foreach ($row as $c){
                        if(!empty($c['local_name'])){

                            $modelname=Yii::$app->path->define($model['data']['post']);
                            $model[1]=new $modelname();
                            //if(!mb_check_encoding($c['local_name'], "UTF-8")){
                            if(strlen($c['local_name']) == mb_strlen($c['local_name'], 'UTF-8')){
                               // $model['language_local']->defineAttribute([]'4-0-local_name');
                                $model['language_local']->addError('local_name','Data Should be in UTF-8 Format');
                                return $this->render('index',['model'=>$model,'searchModel'=>$searchModel,'dataProvider' => $dataProvider,'modelLangauge'=>$modelLangauge]);
                            }
                            if(empty($c['id'])){
                                $sent_opt=INSERT;
                                Yii::$app->operation->defaults($model[1], INSERT);
                            }else{
                                $sent_opt=UPDATE;
                                $model[1] = $model[1]::findOne($c['id']);
                                $history=Yii::$app->path->define($model['data']['post']).'History';
                                $historyModel=new $history();
                                Yii::$app->operation->history($model[1],$historyModel,UPDATE);
                                Yii::$app->operation->defaults($model[1], UPDATE);
                        }
                        $model[1]->is_active=1;
                        $model[1]->local_name=$c['local_name'];
                        $model[1]->language_code=$c['language_code'];

                        $model[1]->{$fields[0]}=$c[$fields[0]];
                        if($model[1]->save()){
                            $sentbox=new \app\models\TblSentbox();
                            if($sentbox->setSentbox($model[1], $sent_opt)){
                                $model[1]->flg_sentbox_entry='Y';
                                $model[1]->save();
                            }
                        }

                    }
                }

            }
            return $this->redirect(['index','l'=>$l]);
            }else
                return $this->render('index',['model'=>$model,'searchModel'=>$searchModel,'dataProvider' => $dataProvider,'modelLangauge'=>$modelLangauge]);
        } else {
           return $this->render('index',['model'=>$model,'searchModel'=>$searchModel,'dataProvider' => $dataProvider,'modelLangauge'=>$modelLangauge]);
        }
    }
         private function getLabels($l){
        $label=[
            'dcs'=>['title'=>'Dcs miscellaneous','grid'=>'Dcs','fields'=>'dcs_miscellaneous_code,dcs_code,description,miscellaneous_code','model'=>'TblDcsMiscellaneous','localmodel'=>'TblDcsMiscellaneousLocal'],
            
            ];
        return $label[$l];

}
}
