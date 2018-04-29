<?php

use yii\helpers\Html;

$this->title = 'Screen 6';
?>

<div class="tbl-states-create">
    <div class="panel panel-main">
        <div class="panel-heading">Village</div>
        <div class="panel-body">
            <div class="panel-subheading">
                <h4>Create New Village</h4>
                <form id="" class="save-form" method="post">
                    <div class="row">
                        <div class="form-group col-sm-3">
                            <label class="control-label">State</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group col-sm-3">
                            <label class="control-label">District</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group col-sm-3">
                            <label class="control-label">Sub-District</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-3">
                            <label class="control-label">Village Code</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group col-sm-3">
                            <label class="control-label">Village Name</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group col-sm-3">
                            <label class="control-label">Name Local</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group col-sm-3">
                            <div class="checkbox">
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
            <div class="table-responsive col-sm-12">
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
        <div class="panel-footer">
            <a href="" class="btn btn-default">Edit</a>
            <a href="" class="btn btn-default">Delete</a>
            <div class="pull-right text-right">
            <a href="" class="btn btn-default disabled">Add Miscellaneous</a>    
            </div>
        </div>
    </div>
</div>