<?php

namespace app\modules\payment\models;

/**
 * This is the ActiveQuery class for [[TblVspPaymentTransaction]].
 *
 * @see TblVspPaymentTransaction
 */
class TblVspPaymentTransactionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblVspPaymentTransaction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblVspPaymentTransaction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
