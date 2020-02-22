<?php

namespace app\modules\bkgprocess\models;

/**
 * This is the ActiveQuery class for [[TblFtpTxnLog]].
 *
 * @see TblFtpTxnLog
 */
class TblFtpTxnLogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblFtpTxnLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblFtpTxnLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
