<?php

namespace app\modules\collection\models;

/**
 * This is the ActiveQuery class for [[TblProcessedFiles]].
 *
 * @see TblProcessedFiles
 */
class TblProcessedFilesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblProcessedFiles[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblProcessedFiles|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
