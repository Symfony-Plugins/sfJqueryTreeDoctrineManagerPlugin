<?php use_helper('I18N', 'Date', 'JavascriptBase') ?>
<?php include_partial('sfJqueryTreeDoctrine/assets') ?>
<div id="sf_admin_container">
    <h1><?php echo sfInflector::humanize(sfInflector::underscore($model)); ?> Nested Set Manager</h1>
    <?php include_partial('sfJqueryTreeDoctrine/flashes') ?>
    
    
    <div class="nested_set_manager_holder" id="<?php echo strtolower($model); ?>_nested_set_manager_holder">
        <?php //echo '<pre>' . print_r($records,1) . '</pre>';?>
        <?php echo get_partial('sfJqueryTreeDoctrine/nested_set_list', array('model' => $model, 'field' => $field, 'root' => $root, 'records' => $records)); ?>
        <div style="clear:both"></div>
    </div>
    
    
    <ul class="sf_admin_actions">
      <?php include_partial('sfJqueryTreeDoctrine/list_batch_actions') ?>
      <?php include_partial('sfJqueryTreeDoctrine/list_actions', array('model' => $model,'field' => $field)) ?>
    </ul>
    
</div>