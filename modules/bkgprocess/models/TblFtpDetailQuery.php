<?php

namespace app\modules\bkgprocess\models;

/**
 * This is the ActiveQuery class for [[TblFtpDetail]].
 *
 * @see TblFtpDetail
 */
class TblFtpDetailQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblFtpDetail[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblFtpDetail|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
