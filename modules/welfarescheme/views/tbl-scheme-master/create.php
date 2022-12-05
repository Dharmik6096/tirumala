<?php
$this->title = Yii::$app->label->title('create', 'Scheme Master');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'create',
        ])
        ?>
    </div>

    <div class="row">
        <div class="form-grid">
            <?=
            $this->render('_form_grid', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ])
            ?>
        </div>
    </div>
</div>

<?php
$script = "
     $('.edit-record').on('click',function(event){       
        var id= $(this).attr('data-val');
        editContactDetail(id);
    });
    
    function editSchemeCriteria(scheme_criteria_id){
            if(scheme_criteria_id != ''){         
            $.ajax({
                    type: 'post',
                    url: '" . Url::to(['/welfarescheme/tbl-scheme-master/scheme-criteria']) . "',
                    data: {'scheme_criteria_id' : scheme_criteria_id},
                    beforeSend:function(data) {
                    $('#loadercontent').show();
                    $('#pageloader').show();
                    },
                    success: function(data) {
                        $.each(data.modelData, function(index, value) {
                            $('#tblcontactdetails-'+index).val(value);
                        });
                         $('#loadercontent').hide();
                         $('#pageloader').hide();
                         $(window).scrollTop(0);

                    },
                });
            }
    };
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>