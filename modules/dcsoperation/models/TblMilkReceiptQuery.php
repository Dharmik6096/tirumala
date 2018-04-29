<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblMilkReceipt]].
 *
 * @see TblMilkReceipt
 */
class TblMilkReceiptQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblMilkReceipt[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblMilkReceipt|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
