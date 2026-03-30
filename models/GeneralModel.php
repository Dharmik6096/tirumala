<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GeneralModel
 *
 * @author aura001
 */

namespace app\models;

use Yii;
use yii\base\UserException;
use ReflectionClass;

class GeneralModel {

    public function __call($name, $arguments) {
        if ($name == 'saveTransaction') {
            switch (count($arguments)) {
                case 0 : return;
                case 1 : return;
                case 2 : return $this->save2($arguments[0], $arguments[1]);
                case 3 : return $this->save3($arguments[0], $arguments[1], $arguments[2]);
                //   case 4 : return $this->save4($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
            }
        } else if ($name == 'deleteTransaction') {
            switch (count($arguments)) {
                case 0 : return;
                case 1 : return $this->delete1($arguments[0]);
                case 2 : return;
                case 3 : return $this->delete3($arguments[0], $arguments[1], $arguments[2]);
            }
        } else if ($name == 'saveDeleteTransaction') {
            switch (count($arguments)) {
                case 0 : return;
                case 1 : return;
                case 2 : return;
                case 3 : return;
                case 4 : return $this->saveDelete4($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
                case 5 : return $this->saveDelete5($arguments[0], $arguments[1], $arguments[2], $arguments[3], $arguments[4]);
            }
        } else if ($name == 'saveTransactionMultiAutoIncForeignKey') {
            switch (count($arguments)) {
                case 3 : return $this->save3MultiAutoIncForeignKey($arguments[0], $arguments[1], $arguments[2]);
            }
        } else if ($name == 'saveTransactionAutoIncForeignKey') {
            switch (count($arguments)) {
                case 3 : return $this->save3AutoIncForeignKey($arguments[0], $arguments[1], $arguments[2]);
            }
        } else if ($name == 'saveTransactionWithSp') {
            switch (count($arguments)) {
                case 3 : return $this->saveWithSp($arguments[0], $arguments[1], $arguments[2]);
            }
        }
    }

    //put your code here
    /*
     * used: state,district,sub-district
     */
    public function save2($model, $message) {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            foreach ($model as $m) {
                $master[] = $m->save();
            }
            if (!in_array(FALSE, $master)) {
                $transaction->commit();
                //var_dump($master);exit;
                if (Yii::$app->request->isConsoleRequest) {
                    $cmd = (Yii::$app->controller->id ?? "") . "/" . (Yii::$app->controller->action->id ?? "");
                    fwrite(STDERR,'[' . date('Y-m-d H:i:s') . '][' . $cmd . ']: ' . Yii::$app->label->message($message[1], $message[0]) . PHP_EOL);
                } else {
                    Yii::$app->display->message(true, $message[0], $message[1]);
                }
                return 'customRedirect';
            } else {
                $child = new ChildModel();
                foreach ($model as $m) {
                    $child->decryptModel($m);
                }
                $transaction->rollback();
                //var_dump($model);exit;
                if (Yii::$app->request->isConsoleRequest) {
                    $cmd = (Yii::$app->controller->id ?? "") . "/" . (Yii::$app->controller->action->id ?? "");
                    fwrite(STDERR,'[' . date('Y-m-d H:i:s') . '][' . $cmd . ']: Your transaction is not saved successfully' . PHP_EOL);
                } else {
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                        'message' => 'Your transaction is not saved successfully']);
                }
                return 'customRender';
            }
        } catch (UserException $e) {
            $transaction->rollback();
            if (Yii::$app->request->isConsoleRequest) {
                $cmd = (Yii::$app->controller->id ?? "") . "/" . (Yii::$app->controller->action->id ?? "");
                fwrite(STDERR,'[' . date('Y-m-d H:i:s') . '][' . $cmd . ']:' . $e->getMessage() . PHP_EOL);
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => $e->getMessage()]);
            }

            return false;
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            $message = htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8');
            if (Yii::$app->request->isConsoleRequest) {
                $cmd = (Yii::$app->controller->id ?? "") . "/" . (Yii::$app->controller->action->id ?? "");
                fwrite(STDERR,'[' . date('Y-m-d H:i:s') . '][' . $cmd . ']:' . $message . PHP_EOL);
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => $message]);
            }

            return false;
        }
    }

    public function save3($model, $childModel, $message) {
        //var_dump($model);var_dump($childModel);exit;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            foreach ($model as $m) {
                $master[] = $m->save();
            }
            if (!in_array(FALSE, $master)) {
                foreach ($childModel as $key => $m) {
                    if ($key != 0 && strpos($childModel[$key - 1]->tableName(), 'history') !== false && $childModel[$key - 1]->operation_type == 'DELETE') {
                        $master[] = $m->delete();
                    } else {
                        $name = (new ReflectionClass($m))->getShortName();
                        if ($name == 'TblContactDetails' || $name == 'TblBankDetails')
                            $master[] = $m->save();
                        else
                            $master[] = $m->save(FALSE);
                    }
                }

                if (!in_array(FALSE, $master)) {
                    $transaction->commit();
                    Yii::$app->display->message(true, $message[0], $message[1]);
                    return 'customRedirect';
                }
            }
            $child = new ChildModel();
            foreach ($childModel as $key => $m) { //this code to get validation msgs of child table when matster validation fails
                if ($key != 0 && strpos($childModel[$key - 1]->tableName(), 'history') !== false && $childModel[$key - 1]->operation_type == 'DELETE') {
                    
                } else {
                    $child->decryptModel($m);
                    $master[] = $m->validate();
                }
            }
            //exit;

            foreach ($model as $m) {
                $child->decryptModel($m);
            }
            foreach ($childModel as $m) {
                $child->decryptModel($m);
            }
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => 'Your transaction is not saved successfully']);
            return 'customRender';
        } catch (UserException $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => $e->getMessage()]);
            return false;
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')]);
            return false;
        }
    }

    public function appTransaction($childModel, $message) {
        // var_dump($childModel);
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            foreach ($childModel as $key => $m) {

                if ($key != 0 && strpos($childModel[$key - 1]->tableName(), 'history') !== false && $childModel[$key - 1]->operation_type == 'DELETE') {
                    $master[] = $m->delete();
                } else {
                    $master[] = $m->save();
                    //var_dump($m->getErrors());
                }
            }
            //exit;
//                var_dump($childModel[0]->getErrors());
            //var_dump($master);exit;
            if (!in_array(FALSE, $master)) {
                $transaction->commit();
                Yii::$app->display->message(true, $message[0], $message[1]);
                return 'customRedirect';
            } else {
                $transaction->rollback();
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => 'Your transaction is not saved successfully']);
                return 'customRender';
            }
        } catch (UserException $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => $e->getMessage()]);
            return false;
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')]);
            return false;
        }
    }

    public function mappingTransaction($revoke, $assign, $modelName, $fields, $code) {

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $count = [];
            foreach ($revoke as $r) {
                $model = Yii::$app->path->define($modelName[0]);
                $modelMapping = $model::find()->where([$fields[0] => $code, $fields[1] => $r])->one();
                $history = Yii::$app->path->define($modelName[1]);
                $historyModel = new $history();
                Yii::$app->operation->history($modelMapping, $historyModel, DELETE);
                $count[] = $historyModel->save();
                $count[] = $modelMapping->delete();
            }
            foreach ($assign as $r) {
                $model = Yii::$app->path->define($modelName[0]);
                $modelMapping = new $model();
                $modelMapping->{$fields[0]} = $code;
                $modelMapping->{$fields[1]} = $r;
                $modelMapping->is_active = 1;
                $count[] = $modelMapping->save(false);
            }
            if (in_array(FALSE, $count)) {
                $transaction->rollback();
                throw new UserException("Due to Validation Error Data is not saved. Please try again!");
            } else {
                $transaction->commit();
                return TRUE;
            }
            return false;
        } catch (UserException $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => $e->getMessage()]);
            return false;
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')]);
            return false;
        }
    }

    public function mappingTransactionMultiField($revoke, $assign, $modelName, $mapping = []) {

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $count = [];
            foreach ($revoke as $r) {
                $model = Yii::$app->path->define($modelName[0]);
                $modelMapping = $model::findOne($r);
                $history = Yii::$app->path->define($modelName[1]);
                $historyModel = new $history();
                Yii::$app->operation->history($modelMapping, $historyModel, DELETE);
                $count[] = $historyModel->save();
                $count[] = $modelMapping->delete();
            }
            foreach ($assign as $r) {
                $model = Yii::$app->path->define($modelName[0]);
                $modelMapping = new $model();
                $modelMapping->attributes = $r;
                //$modelMapping->{$fields[1]} = $r;
                $modelMapping->is_active = 1;
                $count[] = $modelMapping->save(false);
            }
            foreach ($mapping as $m) {
                $count[] = $m->save(false);
            }

            if (in_array(FALSE, $count)) {
                $transaction->rollback();
                throw new UserException("Due to Validation Error Data is not saved. Please try again!");
            } else {
                $transaction->commit();
                return TRUE;
            }
            return false;
        } catch (UserException $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => $e->getMessage()]);
            return false;
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')]);
            return false;
        }
    }

    public function delete1($model) {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            for ($i = 0; $i < count($model); $i += 2) {
                $master[] = $model[$i + 1]->save();
                $master[] = $model[$i]->delete();
            }
            if (in_array(FALSE, $master)) {
                $transaction->rollback();
                $record = ['status' => 'error', 'msg' => 'This record cannot be deleted due to some reference Error.'];
            } else {
                $transaction->commit();
                $record = ['status' => 'success', 'msg' => 'Record is successfully deleted.'];
            }
        } catch (UserException $e) {

            $transaction->rollback();
            $record = ['status' => 'error', 'msg' => $e->getMessage()];
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            $record = ['status' => 'error', 'msg' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')];
        }
        return $record;
    }

    public function delete3($model, $mapping, $field) {
        $transaction = \Yii::$app->db->beginTransaction();
        $flag = '';
        try {
            $master[] = $model[1]->save();
            if (!is_array($field)) {

                //var_dump($master);
                if (!in_array(false, $mapping)) {

                    $flag = $this->deleteMapping($mapping, $field, $model[0]->{$field});
                }
            } else {
                if (!in_array(false, $mapping)) {
                    $flag = $this->deleteMapping($mapping, $field[0], $model[0]->{$field[0]});
                }
                if (in_array('TblContactDetails', $mapping)) {
                    $contactModel = array('TblContactDetails', 'TblContactDetailsHistory');
                    $contactFlag = $this->deleteContacts($contactModel, $model[0]->{$field[0]}, $field[1]);
                }
                if (in_array('TblBankDetails', $mapping)) {
                    $bankModel = array('TblBankDetails', 'TblBankDetailsHistory');
                    '';
                    $bankFlag = $this->deleteBanks($bankModel, $model[0]->{$field[0]}, $field[1]);
                }
            }

            if (is_array($flag)) {
                $tmp = array_merge($master, $flag);
                $master = $tmp;
            }
            if (isset($contactFlag) && is_array($contactFlag)) {
                $tmp = array_merge($master, $contactFlag);
                $master = $tmp;
            }
            if (isset($bankFlag) && is_array($bankFlag)) {
                $tmp = array_merge($master, $flag);
                $master = $tmp;
            }
            $master[] = $model[0]->delete();
            if (in_array(FALSE, $master)) {
                $transaction->rollback();
                $record = ['status' => 'error', 'msg' => 'This record cannot be deleted due to some reference Error.'];
            } else {
                $transaction->commit();
                $record = ['status' => 'success', 'msg' => 'Record is successfully deleted.'];
            }
        } catch (UserException $e) {
            $transaction->rollback();
            $record = ['status' => 'error', 'msg' => $e->getMessage()];
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            $record = ['status' => 'error', 'msg' => htmlspecialchars($e, ENT_QUOTES, 'UTF-8')];
        }
        return $record;
    }

    public static function callSp($spname, $param) {
        try {
            $str = '';
            for ($i = 1; $i <= count($param) + 1; $i++) {
                $str .= '@paramName' . $i . ' =:paramName' . $i . ', ';
            }

            $sql = '{ CALL ' . $spname . ' (' . $str . '@out=:out)}';

            $userCode = \Yii::$app->user->identity->user_code;
            $out = '';
            $command = Yii::$app->db->createCommand($sql);
            foreach ($param as $key => $value) {
                $command->bindParam(':paramName' . ($key + 1), $param[$key]);
            }
            $command->bindParam(':paramName' . (count($param) + 1), $userCode);
            $command->bindParam(":out", $out);

            // $list = $command->execute();
            return $command->queryScalar();
            //echo \Yii::$app->db->createCommand("select @out as result;")->queryAll();
        } catch (Exception $e) {
            Log::trace("Error : " . $e);
            throw new Exception("Error : " . $e);
        }
        return $list;
    }

    public function callSpOld($spname, $param) {
        $str = '';
        for ($i = 1; $i <= count($param) + 1; $i++) {
            $str .= ':paramName' . $i . ',';
        }
        $command = \Yii::$app->db->createCommand("CALL {$spname}({$str}@out)");
        foreach ($param as $key => $value) {
            $command->bindValue(':paramName' . ($key + 1), $value);
        }
        $command->bindValue(':paramName' . (count($param) + 1), \Yii::$app->user->identity->user_code)->execute();
        return \Yii::$app->db->createCommand("select @out as result;")->queryScalar();
    }

    public function deleteMapping($modelName, $fieldName, $fieldValue) {
        $transaction = \Yii::$app->db->beginTransaction();
        $flag = [];
        try {
            $model = Yii::$app->path->define($modelName[0]);

            $data = $model::find()->where([$fieldName => $fieldValue])->all();
            foreach ($data as $row) {
                $modelMappingHistory = Yii::$app->path->getModel($modelName[1]);
                Yii::$app->operation->history($row, $modelMappingHistory, 'DELETE');
                $flag[] = $modelMappingHistory->save();
                $flag[] = $row->delete();
            }
            if (!in_array(FALSE, $flag)) {
                $transaction->commit();
                return $flag;
            } else {
                $transaction->rollback();
                return $flag;
            }
        } catch (UserException $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => $e->getMessage()]);
            return $flag;
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')]);
            return $flag;
        }
    }

    public function deleteContacts($modelName, $code, $type) {

        $model = Yii::$app->path->define($modelName[0]);
        $data = $model::find()->where(['module_code' => $code])->all();
        if ($data) {
            foreach ($data as $row) {
                $modelMappingHistory = Yii::$app->path->getModel($modelName[1]);
                Yii::$app->operation->history($row, $modelMappingHistory, DELETE);
                $flag[] = $modelMappingHistory->save();
                $flag[] = $row->delete();
            }
            return $flag;
        }
        return true;
    }

    public function deleteBanks($modelName, $code, $type) {
        $model = Yii::$app->path->define($modelName[0]);
        $data = $model::find()->where(['module_code' => $code])->all();
        if ($data) {
            foreach ($data as $row) {
                $modelMappingHistory = Yii::$app->path->getModel($modelName[1]);
                Yii::$app->operation->history($row, $modelMappingHistory, DELETE);
                $flag[] = $modelMappingHistory->save();
                $flag[] = $row->delete();
            }
            return $flag;
        }
        return true;
    }

    public function saveDelete5($model, $saveChild, $deleteChild, $message, $returnException = false) {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            foreach ($model as $m) {
                $master[] = $m->save();
            }
            if (!in_array(FALSE, $master)) {
                foreach ($saveChild as $m) {
                    $master[] = $m->save();
                }
            }
            if (!in_array(FALSE, $master)) {
                foreach ($deleteChild as $m) {
                    $master[] = $m->delete();
                }
            }
            if (!in_array(FALSE, $master)) {
                $transaction->commit();
                if (Yii::$app->request->isConsoleRequest) {
                    $cmd = (Yii::$app->controller->id ?? "") . "/" . (Yii::$app->controller->action->id ?? "");
                    fwrite(STDERR,'[' . date('Y-m-d H:i:s') . '][' . $cmd . ']:' . Yii::$app->label->message($message[1], $message[0]) . PHP_EOL);
                } else {
                    Yii::$app->display->message(true, $message[0], $message[1]);
                }
                return 'customRedirect';
            }
            $transaction->rollback();
            if (Yii::$app->request->isConsoleRequest) {
                $cmd = (Yii::$app->controller->id ?? "") . "/" . (Yii::$app->controller->action->id ?? "");
                fwrite(STDERR,'[' . date('Y-m-d H:i:s') . '][' . $cmd . ']: Your transaction is not saved successfully' . PHP_EOL);
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => Yii::t('app', 'Your transaction is not saved successfully')]);
            }
            return 'customRender';
        } catch (UserException $e) {
            $transaction->rollback();
            if (Yii::$app->request->isConsoleRequest) {
                $cmd = (Yii::$app->controller->id ?? "") . "/" . (Yii::$app->controller->action->id ?? "");
                fwrite(STDERR,'[' . date('Y-m-d H:i:s') . '][' . $cmd . ']:' . $e->getMessage() . PHP_EOL);
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => $e->getMessage()]);
            }
            if ($returnException) {
                return $e->getMessage();
            } else {
                return false;
            }
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            $message = htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8');
            if (Yii::$app->request->isConsoleRequest) {
                $cmd = (Yii::$app->controller->id ?? "") . "/" . (Yii::$app->controller->action->id ?? "");
                fwrite(STDERR,'[' . date('Y-m-d H:i:s') . '][' . $cmd . ']:' . $message . PHP_EOL);
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => $message]);
            }
            if ($returnException) {
                return $message;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            $transaction->rollback();
            if (Yii::$app->request->isConsoleRequest) {
                $cmd = (Yii::$app->controller->id ?? "") . "/" . (Yii::$app->controller->action->id ?? "");
                fwrite(STDERR,'[' . date('Y-m-d H:i:s') . '][' . $cmd . ']:' . $e->getMessage() . PHP_EOL);
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => $e->getMessage()]);
            }
            if ($returnException) {
                return $e->getMessage();
            } else {
                return false;
            }
        }
    }

    public function saveDelete4($model, $saveChild, $deleteChild, $message) {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            foreach ($model as $m) {
                $master[] = $m->save();
            }
            if (!in_array(FALSE, $master)) {
                foreach ($saveChild as $m) {
                    $master[] = $m->save();
                }
            }
            if (!in_array(FALSE, $master)) {
                foreach ($deleteChild as $m) {
                    $master[] = $m->delete();
                }
            }
            if (!in_array(FALSE, $master)) {
                $transaction->commit();
                if (Yii::$app->request->isConsoleRequest) {
                    $cmd = (Yii::$app->controller->id ?? "") . "/" . (Yii::$app->controller->action->id ?? "");
                    fwrite(STDERR,'[' . date('Y-m-d H:i:s') . '][' . $cmd . ']:' . Yii::$app->label->message($message[1], $message[0]) . PHP_EOL);
                } else {
                    Yii::$app->display->message(true, $message[0], $message[1]);
                }
                return 'customRedirect';
            }
            $transaction->rollback();
            if (Yii::$app->request->isConsoleRequest) {
                $cmd = (Yii::$app->controller->id ?? "") . "/" . (Yii::$app->controller->action->id ?? "");
                fwrite(STDERR,'[' . date('Y-m-d H:i:s') . '][' . $cmd . ']: Your transaction is not saved successfully' . PHP_EOL);
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => Yii::t('app', 'Your transaction is not saved successfully')]);
            }
            return 'customRender';
        } catch (UserException $e) {
            $transaction->rollback();
            if (Yii::$app->request->isConsoleRequest) {
                $cmd = (Yii::$app->controller->id ?? "") . "/" . (Yii::$app->controller->action->id ?? "");
                fwrite(STDERR,'[' . date('Y-m-d H:i:s') . '][' . $cmd . ']:' . $e->getMessage() . PHP_EOL);
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => $e->getMessage()]);
            }
            return false;
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            if (Yii::$app->request->isConsoleRequest) {
                $cmd = (isset(Yii::$app->controller->id) ? Yii::$app->controller->id : "") . "/" . (isset(Yii::$app->controller->action->id) ? Yii::$app->controller->action->id : "");
               fwrite(STDERR,$cmd . ' : ' . htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8') . PHP_EOL);
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')]);
            }
            return false;
        }
    }

    public function save3MultiAutoIncForeignKey($model, $message, $auto_key_config) {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            foreach ($model as $i => $m) {
                if (!empty($auto_key_config[$i])) {
                    $setModelKey = $auto_key_config[$i]['self_key'];
                    $parentModelKey = $auto_key_config[$i]['parent_key'];
                    $parentModelIndex = $auto_key_config[$i]['parent_index'];
                    $m->{$setModelKey} = $model[$parentModelIndex]->{$parentModelKey};
                }
                $master[] = $m->save();
            }
            if (!in_array(FALSE, $master)) {
                $transaction->commit();
                Yii::$app->display->message(true, $message[0], $message[1]);
                return 'customRedirect';
            } else {
                $child = new ChildModel();
                foreach ($model as $m) {
                    $child->decryptModel($m);
                }
                $transaction->rollback();
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => 'Your transaction is not saved successfully']);
                return 'customRender';
            }
        } catch (UserException $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => $e->getMessage()]);
            return false;
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')]);
            return false;
        }
    }

    public function save3AutoIncForeignKey($model, $message, $auto_key_config) {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            foreach ($model as $m) {
                $m_name = $m::className();
                $m_name = explode("\\", $m_name);
                $m_name = $m_name[count($m_name) - 1];
                if (!empty($auto_key_config[$m_name])) {
                    foreach ($auto_key_config[$m_name] as $key_config) {
                        $m->{$key_config['self_key']} = $model[$key_config['parent_index']]->{$key_config['parent_key']};
                    }
                }
                $master[] = $m->save();
            }
            if (!in_array(FALSE, $master)) {
                $transaction->commit();
                Yii::$app->display->message(true, $message[0], $message[1]);
                return 'customRedirect';
            } else {
                $child = new ChildModel();
                foreach ($model as $m) {
                    $child->decryptModel($m);
                }
                $transaction->rollback();
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => 'Your transaction is not saved successfully']);
                return 'customRender';
            }
        } catch (UserException $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => $e->getMessage()]);
            return false;
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')]);
            return false;
        }
    }

    public function saveWithSp($model, $spCall, $message) {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            foreach ($spCall as $sp) {
                $result = \Yii::$app->general->getSpData($sp[0], $sp[1]);
                foreach ($result as $res) {
                    $master[] = $res['retuns_value'];
                }
            }
            if (!in_array(FALSE, $master)) {
                foreach ($model as $m) {
                    $master[] = $m->save();
                }
            }
            if (!in_array(FALSE, $master)) {
                $transaction->commit();
                Yii::$app->display->message(true, $message[0], $message[1]);
                return 'customRedirect';
            } else {
                $child = new ChildModel();
                foreach ($model as $m) {
                    $child->decryptModel($m);
                }
                $transaction->rollback();
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => 'Your transaction is not saved successfully']);
                return 'customRender';
            }
        } catch (UserException $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => $e->getMessage()]);
            return false;
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')]);
            return false;
        }
    }

}

?>
