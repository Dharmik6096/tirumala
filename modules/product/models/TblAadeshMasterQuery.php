<?php

namespace app\modules\product\models;

/**
 * This is the ActiveQuery class for [[TblAadeshMaster]].
 *
 * @see TblAadeshMaster
 */
class TblAadeshMasterQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblAadeshMaster[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblAadeshMaster|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
