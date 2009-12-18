<?php if( isset($records) && is_object($records) && $records->count() > 0 ): ?>

<div id="<?php echo strtolower($model);?>-nested-set">
<ul class="nested_set_list">
<?php foreach($records as $record): ?>
    <li id="phtml_<?php echo $record->id;?>" class="open">
         <div class="label"><?php echo $record->$field;?>
         
         <div class="links">les liens</div>
         </div>
         

    </li>

  <?php endforeach; ?>

</ul>

<?php endif;?>
<?php echo javascript_tag();?>
$(function () { 
    $("#<?php echo strtolower($model);?>-nested-set").tree();
});
<?php echo end_javascript_tag();?>