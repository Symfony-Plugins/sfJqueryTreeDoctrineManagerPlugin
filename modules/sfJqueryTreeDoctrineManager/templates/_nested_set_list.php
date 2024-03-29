 <?php if( isset($records) && is_object($records) && count($records) > 0 ): ?>
    <div id="<?php echo strtolower($model);?>-nested-set">
        <ul class="nested_set_list">
<?php       
	        $prevLevel = false;
            $levels = 0;
            $i = 1;
?>    
<?php       foreach($records as $record): ?>
<?php           if($prevLevel > 0 && $record['level'] == $prevLevel): ?>
        	</li> 
<?php           endif; ?>
<?php           if($prevLevel === false){ $prevLevel = $record['level']; }?>
<?php           if($record['level'] > $prevLevel): $levels++; ?> 
		<ul>
		
<?php           elseif($record['level'] < $prevLevel): ?>
            <?php echo  str_repeat('</li></ul>', $prevLevel - $record['level']); ?> 
<?php           endif; ?>
            <li id="phtml_<?php echo $record->id ?>">
                <a href="<?php echo ($url = sfConfig::get('app_sfJqueryTree_route_edit'))? url_for($url, $record):'#' ?>"><ins>&nbsp;</ins><?php echo $record->$field;?></a>
<?php          $prevLevel = $record['level']; ?>
<?php      endforeach; ?>  
<?php      if($prevLevel > 0 && $record['level'] == $prevLevel): ?>
            </li> 
<?php      endif; ?>      
<?php for($i=$prevLevel;$i<=$levels;$i++):?>
		</ul>
<?php endfor;?>
        </ul>
    </div>
<?php endif;?>

<script type="text/javascript">
<!--
$(function () {
	$("#<?php echo strtolower($model);?>-nested-set").tree({
  	plugins : { 
			cookie : { prefix : "<?php echo strtolower($model);?>_jstree_" },
            <?php if ( sfConfig::get('app_sfJqueryTree_withContextMenu') ): ?>
            contextmenu : { 
				items : {
					// get rid of the remove item
					create : false,
					rename : false,
					remove : false,
					// add an item of our own
					edit : {
						label	: "<?php echo __('edit object');?>", 
						icon	: "/sfDoctrinePlugin/images/edit.png", // you can set this to a classname or a path to an icon like ./myimage.gif
						visible	: function (NODE, TREE_OBJ) { 
							// this action will be disabled if more than one node is selected
							if(NODE.length != 1) return 0; 
							// this action will not be in the list if condition is met
							if(TREE_OBJ.get_text(NODE) == "Child node 1") return -1; 
							// otherwise - OK
							return 1; 
						}, 
						action	: function (NODE, TREE_OBJ) {
<?php 
	if(sfConfig::has('app_sfJqueryTree_route_edit')){
		$url = "$(NODE).find('a').attr('href')";
	}else{
		$url = "'".strtolower($model)."/' +  NODE.attr('id').replace('phtml_','') + '/edit'";
	}
?>
								document.location.href = <?php echo $url ?>;
						},
						separator_before : true
					}
				}
			}

            <?php endif; ?>
            
            
		},
		
		callback: {// activate add and delete node button
			onchange: function(){ $('.nodeinteraction').attr('disabled','');},
      
			onrename : function (NODE, TREE_OBJ, RB) {
                $('.error').remove();
                $('.notice').remove();
				$('.nested_set_manager_holder').before('<div class="waiting"><?php echo __('Sending data to server.');?><\/div>');
                if (TREE_OBJ.get_text(NODE) == 'New folder'){
                    $('.nested_set_manager_holder').before('<div class="error">"'+TREE_OBJ.get_text(NODE)+'" <?php echo __('is not a valid name');?><\/div>');
                    $.tree.focused().rename();
                }
                else {
                    if (NODE.id == ''){ // happen if creation of a new node
                        $.ajax({
                            type: "POST",
                            url : '<?php echo url_for('sfJqueryTreeDoctrineManager/Add_child');?>',
                            dataType : 'json',
                            data : 'root=<?php echo $root;?>&model=<?php echo $model;?>&field=<?php echo $field;?>&value='+TREE_OBJ.get_text(NODE)+'&parent_id=' + TREE_OBJ.parent(NODE).attr('id').replace('phtml_',''),
                            complete : function(){ 
                                    $('.waiting').remove();
                            },
                            success : function (data, textStatus) {
                                $('.nested_set_manager_holder').before('<div class="notice"><?php echo __('The item was created successfully.');?><\/div>');
                                $(NODE).attr('id','phtml_'+data.id);
                            },
                            error : function (data, textStatus) {
                                $('.nested_set_manager_holder').before('<div class="error"><?php echo __('Error while creating the item.');?><\/div>');
                                $.tree.rollback(RB);
                            }
                        });
                    }
					else { // happen when renaming an existing node
						$.ajax({
							type: "POST",
							url : '<?php echo url_for('sfJqueryTreeDoctrineManager/Edit_field');?>',
							dataType : 'json',
							data : 'root=<?php echo $root;?>&model=<?php echo $model;?>&field=<?php echo $field;?>&value='+TREE_OBJ.get_text(NODE)+'&id=' + NODE.id.replace('phtml_',''),
							complete : function(){ 
								$('.waiting').remove();
							},
							success : function (data, textStatus) {
								$('.nested_set_manager_holder').before('<div class="notice"><?php echo __('The item was renamed successfully.');?><\/div>');
							},
							error : function (data, textStatus) {
								$('.nested_set_manager_holder').before('<div class="error"><?php echo __('Error while renaming the item.');?><\/div>');
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
				$('.nested_set_manager_holder').before('<div class="waiting"><?php echo __('Sending data to server.');?><\/div>');
				$.ajax({
					type: "POST",
					url : '<?php echo url_for('sfJqueryTreeDoctrineManager/Move');?>',
					dataType : 'json',
					data : 'root=<?php echo $root;?>&model=<?php echo $model;?>&id=' + NODE.id.replace('phtml_','') +'&to_id=' + REF_NODE.id.replace('phtml_','') + '&movetype=' + TYPE,
					complete : function(){
						$('.waiting').remove();
					},
					success : function (data, textStatus) {
						$('.nested_set_manager_holder').before('<div class="notice"><?php echo __('The item was moved successfully.');?><\/div>');
					},
					error : function (data, textStatus) {
						$('.nested_set_manager_holder').before('<div class="error"><?php echo __('Error while moving the item.');?><\/div>');
						$.tree.rollback(RB);
					}
				});
			},
			
			ondelete: function(NODE, TREE_OBJ, RB){
				$('.error').remove();
				$('.notice').remove();
				$('.nested_set_manager_holder').before('<div class="waiting"><?php echo __('Sending data to server.');?><\/div>');
				$.ajax({
					type: "POST",
					url : '<?php echo url_for('sfJqueryTreeDoctrineManager/Delete');?>',
					dataType : 'json',
					data : 'root=<?php echo $root;?>&model=<?php echo $model;?>&id=' + NODE.id.replace('phtml_',''),
					complete : function(){ 
						$('.waiting').remove();
					},
					success : function (data, textStatus) {
						$('.nested_set_manager_holder').before('<div class="notice"><?php echo __('The item was deleted successfully.');?><\/div>');
					},
					error : function (data, textStatus) {
						$('.nested_set_manager_holder').before('<div class="error"><?php echo __('Error while deleting the item.');?><\/div>');
						$.tree.rollback(RB);
					}
				});
			}
		}
	});
});

//-->
</script>