<?php

namespace app\modules\dynamicreport\models;

/**
 * This is the ActiveQuery class for [[TblReportList]].
 *
 * @see TblReportList
 */
class TblReportListQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblReportList[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblReportList|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
