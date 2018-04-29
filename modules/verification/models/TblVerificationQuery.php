<?php

namespace app\modules\verification\models;

/**
 * This is the ActiveQuery class for [[TblVerification]].
 *
 * @see TblVerification
 */
class TblVerificationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblVerification[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblVerification|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
