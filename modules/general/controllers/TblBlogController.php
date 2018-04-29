<?php

namespace app\modules\general\controllers;

use Yii;
use app\modules\general\models\TblBlog;
use app\modules\general\models\TblBlogSearch;
use app\modules\general\models\TblBlogAttachment;
use app\modules\general\models\TblBlogAttachmentSearch;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TblBlogController implements the CRUD actions for TblBlog model.
 */
class TblBlogController extends \app\controllers\ChildController
{
    /**
     * Lists all TblBlog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblBlogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'flag'=>'list'
        ]);
    }
    public function actionBlogList()
    {
        $searchModel = new TblBlogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'flag'=>'grid'
        ]);
    }
    /**
     * Displays a single TblBlog model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
       return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    /**
     * Displays a single TblBlog model.
     * @param integer $id
     * @return mixed
     */
    public function actionAuthorView($id)
    {
        $searchModel = new TblBlogAttachmentSearch();
        $searchModel->blog_id=$id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('author_view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Creates a new TblBlog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TblBlog();
        $attachModel= new TblBlogAttachment();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'attachModel'=>$attachModel
            ]);
        }
    }

    /**
     * Updates an existing TblBlog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $attachModel= new TblBlogAttachment();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'attachModel'=>$attachModel
            ]);
        }
    }

    /**
     * Deletes an existing TblBlog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDeleteAttachment($id)
    {
        TblBlogAttachment::findOne($id)->delete();
        return $this->redirect(['index']);
    }
    /**
     * Finds the TblBlog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblBlog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblBlog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
