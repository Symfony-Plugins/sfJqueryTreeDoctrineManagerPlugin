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
                $('.notice').remove();
                if (TREE_OBJ.get_text(NODE) == 'New folder'){
                    $('.nested_set_manager_holder').before('<div class="notice">"'+TREE_OBJ.get_text(NODE)+'" <?php echo __('is not a valid name');?></div>');
                    $.tree.focused().rename();
                }
                else {
                    if (NODE.id == ''){ // happen if creation of a new node
                        $.post( '<?php echo url_for('sfJqueryTreeDoctrineManager/Add_child');?>' , 
                                'model=<?php echo $model;?>&root=<?php echo $root;?>&field=<?php echo $field;?>&value='+TREE_OBJ.get_text(NODE)+'&parent_id=' + TREE_OBJ.parent(NODE).attr('id').replace('phtml_','') , 
                                function(){ 
                                }
                        );
                        
                    }
                    else { // happen when renaming an existing node
                        $.post( '<?php echo url_for('sfJqueryTreeDoctrineManager/Edit_field');?>' , 
                                'model=<?php echo $model;?>&field=<?php echo $field;?>&value='+TREE_OBJ.get_text(NODE)+'&id=' + NODE.id.replace('phtml_','') , 
                                function(){
                                    
                                }
                        );
                        
                    }
                }
			},
            
            ondblclk : function (NODE, TREE_OBJ){
                $.tree.focused().rename();
            },
            
            onmove: function(NODE, REF_NODE, TYPE, TREE_OBJ, RB){
                 $.post( '<?php echo url_for('sfJqueryTreeDoctrineManager/Move');?>' , 
                                'model=<?php echo $model;?>&id=' + NODE.id.replace('phtml_','') +'&to_id=' + REF_NODE.id.replace('phtml_','') + '&movetype=' + TYPE, 
                                function(){
                                    
                                }
                        );
            },
            ondelete: function(NODE, TREE_OBJ, RB){
                $.post( '<?php echo url_for('sfJqueryTreeDoctrineManager/Delete');?>' , 
                                'model=<?php echo $model;?>&id=' + NODE.id.replace('phtml_','') , 
                                function(){
                                    
                                }
                        );
            } 
        }
    });
});
<?php echo end_javascript_tag();?>