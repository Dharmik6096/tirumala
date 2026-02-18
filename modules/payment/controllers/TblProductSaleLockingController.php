<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblProductSaleLocking;
use app\modules\payment\models\TblProductSaleLockingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\payment\models\TblProductSaleTransaction;
use app\modules\payment\models\TblProductSaleTransactionHistory;
use app\modules\payment\models\TblProductSaleTransactionSearch;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * TblProductSaleLockingController implements the CRUD actions for TblProductSaleLocking model.
 */
class TblProductSaleLockingController extends \app\controllers\ChildController {

    /**
     * Lists all TblProductSaleLocking models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblProductSaleLockingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblProductSaleLocking model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        $searchModel = new TblProductSaleTransactionSearch();
        $searchModel->data_lock = 1;
        $searchModel->lock_date = $model->locking_date;
        $searchModel->reference_code = $id;
        $dataProvider = $searchModel->viewsearch(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $model,
                    'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblProductSaleLocking model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $searchModel = new TblProductSaleLockingSearch();
        $searchModel->scenario = 'saleLockData';
        $saveModel = [];
        $dataProvider = $searchModel->locksearch(Yii::$app->request->queryParams);
        if (Yii::$app->request->post()) {
            $searchData = Yii::$app->request->post()['TblProductSaleLockingSearch'];
            $postCodes = Yii::$app->request->post()['product_sale_transaction_code'];
            $model = new TblProductSaleLocking();
            $model->setAttributes($searchData);
            $model->locking_code = Yii::$app->general->getCodeAutoIncrement($model);
            $model->from_date = !empty($model->from_date) ? date('Y-m-d', strtotime($model->from_date)) : '';
            $model->to_date = !empty($model->to_date) ? date('Y-m-d', strtotime($model->to_date)) : '';
            $model->locking_date = !empty($model->locking_date) ? date('Y-m-d', strtotime($model->locking_date)) : '';
            $model->total_count = count($postCodes);
            if (Yii::$app->request->post()['submitType'] == 'lock') {
                $saveModel[] = $model;
                foreach ($postCodes as $updateData) {
                    if (!empty($updateData)) {
                        $modelSale = new TblProductSaleTransaction();
                        $existSale = $modelSale->find()->where(['product_sale_transaction_code' => $updateData])->one();
                        $historyModel = new TblProductSaleTransactionHistory();
                        Yii::$app->operation->history($existSale, $historyModel, UPDATE);
                        $saveModel[] = $historyModel;
                        //$existSale->scenario = 'locksale';
                        $existSale->data_lock = 1;
                        $existSale->lock_date = $model->locking_date;
                        $existSale->reference_code = $model->locking_code;
                        $saveModel[] = $existSale;
                    }
                }
                $transaction = $this->generalModel->saveTransaction($saveModel, ['Product Sale Data Lock', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            } else {
//                $dataProviderDownload = $searchModel->downloadsearch(Yii::$app->request->queryParams);
                $this->downloadData($dataProvider->getModels());
            }
        }
        // $dataProvider = $searchModel->locksearch(Yii::$app->request->queryParams);
        return $this->render('create', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing TblProductSaleLocking model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->locking_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblProductSaleLocking model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblProductSaleLocking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblProductSaleLocking the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblProductSaleLocking::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionExportProductSale($id) {
        $model = $this->findModel($id);
        $controls = [];
        $controls['ref_code'] = $id;
        $controls['lock_date'] = $model->locking_date;
        $output = \Yii::$app->general->getSpData('Portal_download_product_sale_lock_data', $controls);


        $header = [
            'mime' => '	application/vnd.ms-excel',
            'extension' => 'xls',
            'writer' => IOFactory::WRITER_XLS,
        ];
        $objPHPExcel = new Spreadsheet();
        $sheet = $objPHPExcel->getActiveSheet();
        /* $objPHPExcel->getDefaultStyle()
          ->getNumberFormat()
          ->setFormatCode(
          \PHPExcel_Style_NumberFormat::FORMAT_TEXT
          ); */
        $file_header = !empty($output) ? array_keys($output[0]) : [];
        /* $file_header = array_map(function($file_header) {
          return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $file_header))));
          }, array_values($file_header)); */

        $sheet->fromArray(
                $file_header, // The data to set
                NULL, // Array values with this value will not be set
                'A1'         // Top left coordinate of the worksheet range where
//    we want to set these values (default is A1)
        );
        $sheet->fromArray(
                $output, // The data to set
                NULL, // Array values with this value will not be set
                'A2'         // Top left coordinate of the worksheet range where
//    we want to set these values (default is A1)
        );
        $labelArray = !empty($output) ? array_keys($output[0]) : [];
        $labelT = 'ProductSaleLock' . '-' . date('Ymdhis');
        $fileName = $labelT . '.' . $header['extension'] .
                header('Content-Type: ' . $header['mime']);
//        $fileName = $this->data['title'] . '-' . date('Ymdhis') . '.' . $header['extension'] .
//                header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        if (ob_get_contents()) ob_end_clean();
        $objWriter = IOFactory::createWriter($objPHPExcel, IOFactory::WRITER_XLS);
        $objWriter->save('php://output');
        exit();
    }

    public function downloadData($downloadDetail) {
        $header = [
            'mime' => '	application/vnd.ms-excel',
            'extension' => 'xls',
            'writer' => IOFactory::WRITER_XLS,
        ];
        $objPHPExcel = new Spreadsheet();
        $sheet = $objPHPExcel->getActiveSheet();
        /* $objPHPExcel->getDefaultStyle()
          ->getNumberFormat()
          ->setFormatCode(
          \PHPExcel_Style_NumberFormat::FORMAT_TEXT
          ); */
        if (!empty($downloadDetail)) {
            for ($i = 0; $i < count($downloadDetail); $i++) {
                if (isset($downloadDetail[$i]['product_sale_transaction_code'])) {
                    unset($downloadDetail[$i]['product_sale_transaction_code']);
                }
            }
        }
        $file_header = !empty($downloadDetail) ? array_keys($downloadDetail[0]) : [];
        /* $file_header = array_map(function($file_header) {
          return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $file_header))));
          }, array_values($file_header)); */

        $sheet->fromArray(
                $file_header, // The data to set
                NULL, // Array values with this value will not be set
                'A1'         // Top left coordinate of the worksheet range where
//    we want to set these values (default is A1)
        );
        $sheet->fromArray(
                $downloadDetail, // The data to set
                NULL, // Array values with this value will not be set
                'A2'         // Top left coordinate of the worksheet range where
//    we want to set these values (default is A1)
        );
        $labelArray = !empty($downloadDetail) ? array_keys($downloadDetail[0]) : [];
        $labelT = 'ProductSaleLock' . '-' . date('Ymdhis');
        $fileName = $labelT . '.' . $header['extension'] .
                header('Content-Type: ' . $header['mime']);
//        $fileName = $this->data['title'] . '-' . date('Ymdhis') . '.' . $header['extension'] .
//                header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        $objWriter = IOFactory::createWriter($objPHPExcel, IOFactory::WRITER_XLS);
        ob_end_clean();
        $objWriter->save('php://output');
        exit();
    }

}
