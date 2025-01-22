<?php
namespace app\components;

use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Worksheet extends \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
{
    public function fromArray(array $source, $nullValue = null, $startCell = 'A1', $strictNullComparison = false)
    {
        if (!is_array(end($source))) {
            $source = [$source];
        }
        [$startColumn, $startRow] = Coordinate::coordinateFromString($startCell);
        foreach ($source as $rowData) {
            $currentColumn = $startColumn;
            foreach ($rowData as $cellValue) {
                if ($strictNullComparison) {
                    if ($cellValue !== $nullValue) {
                        $this->getCell($currentColumn . $startRow)->setValue($cellValue);
                    }
                } else {
                    if ($cellValue != $nullValue) {
                        if (is_numeric($cellValue) && preg_match('/^([0-9]+)$/', $cellValue)) {
                            $this->setCellValueExplicit($currentColumn . $startRow, $cellValue, DataType::TYPE_STRING);
                        } else {
                            $this->getCell($currentColumn . $startRow)->setValue($cellValue);
                        }
                    }
                }
                ++$currentColumn;
            }
            ++$startRow;
        }
        return $this;        
    }
}
?>