<?php

namespace app\modules\complaint\models;

/**
 * This is the ActiveQuery class for [[TblComplain]].
 *
 * @see TblComplain
 */
class TblComplaintQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblComplain[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblComplain|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
