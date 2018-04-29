<?php

namespace app\modules\general\models;

/**
 * This is the ActiveQuery class for [[TblDocumentMaster]].
 *
 * @see TblDocumentMaster
 */
class TblDocumentMasterQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDocumentMaster[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDocumentMaster|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
