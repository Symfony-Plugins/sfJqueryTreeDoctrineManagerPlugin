<?php if( isset($records) && is_object($records) && $records->count() > 0 ): ?>
    <div id="<?php echo strtolower($model);?>-nested-set">
        <ul class="nested_set_list">
        <?php $prevLevel = 0;?>
        
        <?php foreach($records AS $record): ?>
            <?php if($prevLevel > 0 && $record->getNode()->getLevel() == $prevLevel)  echo '</li>';
            if($record->getNode()->getLevel() > $prevLevel)  echo '<ul>'; 
            elseif ($record->getNode()->getLevel() < $prevLevel) echo str_repeat('</ul></li>', $prevLevel - $record->getNode()->getLevel()); ?>
            <li id ="phtml_<?php echo $record->id ?>" class="open">
                <a href="#"><ins>&nbsp;</ins><?php echo $record->$field;?></a> 
            <?php $prevLevel = $record->getNode()->getLevel();
        endforeach; ?>

        
        
        
        
        
        </ul>
    </div>
<?php endif;?>
<?php echo javascript_tag();?>
$(function () { 

    $("#<?php echo strtolower($model);?>-nested-set").tree({
        callback: {
            // activate add and delete node button
            onchange: function(){ $('.nodeinteraction').attr('disabled','');},
            
            onrename : function (NODE, TREE_OBJ, RB) {
                $('.error').remove();
                $('.notice').remove();
                if (TREE_OBJ.get_text(NODE) == 'New folder'){
                    $('.nested_set_manager_holder').before('<div class="error">"'+TREE_OBJ.get_text(NODE)+'" <?php echo __('is not a valid name');?></div>');
                    $.tree.focused().rename();
                }
                else {
                    if (NODE.id == ''){ // happen if creation of a new node
                        $.ajax({
                            type: "POST",
                            url : '<?php echo url_for('sfJqueryTreeDoctrineManager/Add_child');?>',
                            dataType : 'json',
                            data : 'model=<?php echo $model;?>&root=<?php echo $root;?>&field=<?php echo $field;?>&value='+TREE_OBJ.get_text(NODE)+'&parent_id=' + TREE_OBJ.parent(NODE).attr('id').replace('phtml_',''),
                            success : function (data, textStatus) {
                                $('.nested_set_manager_holder').before('<div class="notice"><?php echo __('The item was created successfully.');?></div>');
                            },
                            error : function (data, textStatus) {
                                $('.nested_set_manager_holder').before('<div class="error"><?php echo __('Error while creating the item.');?></div>');
                                $.tree.rollback(RB);
                            }

                        });
                        
                        
                    }
                    else { // happen when renaming an existing node
                        
                        $.ajax({
                            type: "POST",
                            url : '<?php echo url_for('sfJqueryTreeDoctrineManager/Edit_field');?>',
                            dataType : 'json',
                            data : 'model=<?php echo $model;?>&field=<?php echo $field;?>&value='+TREE_OBJ.get_text(NODE)+'&id=' + NODE.id.replace('phtml_',''),
                            success : function (data, textStatus) {
                                $('.nested_set_manager_holder').before('<div class="notice"><?php echo __('The item was renamed successfully.');?></div>');
                            },
                            error : function (data, textStatus) {
                                $('.nested_set_manager_holder').before('<div class="error"><?php echo __('Error while renaming the item.');?></div>');
                                $.tree.rollback(RB);
                            }
                        });
 
                    }
                }
			},
            
            ondblclk : function (NODE, TREE_OBJ){
                $('.error').remove();
                $('.notice').remove();
                $.tree.focused().rename();
            },
            
            onmove: function(NODE, REF_NODE, TYPE, TREE_OBJ, RB){
                $('.error').remove();
                $('.notice').remove();
                
                $.ajax({
                    type: "POST",
                    url : '<?php echo url_for('sfJqueryTreeDoctrineManager/Move');?>',
                    dataType : 'json',
                    data : 'model=<?php echo $model;?>&id=' + NODE.id.replace('phtml_','') +'&to_id=' + REF_NODE.id.replace('phtml_','') + '&movetype=' + TYPE, 
                    success : function (data, textStatus) {
                        $('.nested_set_manager_holder').before('<div class="notice"><?php echo __('The item was moved successfully.');?></div>');
                    },
                    error : function (data, textStatus) {
                        $('.nested_set_manager_holder').before('<div class="error"><?php echo __('Error while moving the item.');?></div>');
                        $.tree.rollback(RB);
                    }
                });
                
                
                $.post( '<?php echo url_for('sfJqueryTreeDoctrineManager/Move');?>' , 
                            'model=<?php echo $model;?>&id=' + NODE.id.replace('phtml_','') +'&to_id=' + REF_NODE.id.replace('phtml_','') + '&movetype=' + TYPE, 
                            function(data,textStatus){ 
                                console.log(data);
                                console.log(textStatus);
                                console.log(this);
                            }
                    );
                    
                    
            },
            ondelete: function(NODE, TREE_OBJ, RB){
                $('.error').remove();
                $('.notice').remove();
                
                $.ajax({
                    type: "POST",
                    url : '<?php echo url_for('sfJqueryTreeDoctrineManager/Delete');?>',
                    dataType : 'json',
                    data : 'model=<?php echo $model;?>&id=' + NODE.id.replace('phtml_','') , 
                    success : function (data, textStatus) {
                        $('.nested_set_manager_holder').before('<div class="notice"><?php echo __('The item was deleted successfully.');?></div>');
                    },
                    error : function (data, textStatus) {
                        $('.nested_set_manager_holder').before('<div class="error"><?php echo __('Error while deleting the item.');?></div>');
                        $.tree.rollback(RB);
                    }
                });
            } 
        }
    });
});
<?php echo end_javascript_tag();?>