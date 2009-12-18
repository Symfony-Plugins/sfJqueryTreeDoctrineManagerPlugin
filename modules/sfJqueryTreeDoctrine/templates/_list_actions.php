<form id="<?php echo strtolower($model);?>_root_create" action="<?php echo url_for('sfJsTreeDoctrine/Add_root');?>" method="post">
    <div>
        <label for="<?php echo strtolower($model);?>_<?php echo $field;?>"><?php echo ucfirst($field);?> : </label>
        <input type="text" id="<?php echo strtolower($model);?>_<?php echo $field;?>" value="" name="<?php echo strtolower($model);?>[<?php echo $field;?>]"/>
        <input type="hidden" name="model" value="<?php echo $model;?>"/>
        <input type="submit" value="<?php echo __('Create Root');?>"/>
    </div>
</form>

<?php echo javascript_tag();?>
$(document).ready(function(e){
    $('#<?php echo strtolower($model);?>_root_create').submit(function(e){
        //e.preventDefault();
        alert('fd');
        
    });
});
<?php echo end_javascript_tag();?>