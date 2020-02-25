<?php

namespace app\modules\payment\models;

/**
 * This is the ActiveQuery class for [[TblVspPayment]].
 *
 * @see TblVspPayment
 */
class TblVspPaymentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblVspPayment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblVspPayment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
