<?php

namespace app\modules\sms\models;

/**
 * This is the ActiveQuery class for [[TblApiDetail]].
 *
 * @see TblApiDetail
 */
class TblApiDetailQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TblApiDetail[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TblApiDetail|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
