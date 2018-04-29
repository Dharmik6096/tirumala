<?php

namespace app\modules\payment\models;

/**
 * This is the ActiveQuery class for [[TblReversePaymentFileLog]].
 *
 * @see TblReversePaymentFileLog
 */
class TblReversePaymentFileLogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblReversePaymentFileLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblReversePaymentFileLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
