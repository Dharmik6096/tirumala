<?php

use yii\helpers\Html;

$this->title = 'Screen 9';
?>

<div class="tbl-states-create">
    <div class="panel panel-main">
        <div class="panel-heading">Create New Bank</div>
        <div class="panel-body">
            <div class="panel-subheading">
                <form id="" class="save-form" method="post">
                    <div class="row">
                        <div class="form-group col-sm-2">
                            <label class="control-label">Code</label>
                            <input type="text" class="form-control" value="0003">
                        </div>
                        <div class="form-group col-sm-5">
                            <label class="control-label">Bank Name</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group col-sm-5">
                            <label class="control-label">Bank Name(Local)</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-2">
                            <label class="control-label">Length Of A/C No</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group col-sm-10">
                            <div class="checkbox">
                                <label><input type="checkbox"> Check A/C No</label>
                                <label><input type="checkbox"> Nationalized Bank</label>
                                <label><input type="checkbox"> Is Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-12">
                            <button type="submit" class="btn btn-default apply-shortcut">Save</button>
                            <button type="reset" class="btn btn-default apply-shortcut">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
            <!--<div class="row">-->
            <div class="col-sm-3 padding-right-0">
                <div class="table-top">
                    <label>State</label>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-main selected">
                        <tbody>
                            <tr>
                                <td>Gujarat</td>
                            </tr>
                            <tr>
                                <td>Raj</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-9">
                <div class="table-top">
                    <label>District</label>
                    <div class="pull-right">
                        <label><input type="checkbox"> Select All</label>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-main">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Village Name</th>
                                <th>Village Name(Local)</th>
                                <th>Sub-District</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>000001</td>
                                <td>Kuvasana</td>
                                <td></td>
                                <td>Visnagar</td>
                                <td>ACTIVE</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--</div>-->
        </div>
    </div>
</div>