<?php
    
		function get_nested_set_manager($model, $field, $root = 0){
        sfContext::getInstance()->getResponse()->addStylesheet('/sfJqueryTreeDoctrineManagerPlugin/jsTree/themes/default/style.css');
        sfContext::getInstance()->getResponse()->addStylesheet('/sfJqueryTreeDoctrineManagerPlugin/css/screen.css');
        sfContext::getInstance()->getResponse()->addJavascript('/sfJqueryTreeDoctrineManagerPlugin/jsTree/lib/jquery.js');
        sfContext::getInstance()->getResponse()->addJavascript('/sfJqueryTreeDoctrineManagerPlugin/jsTree/jquery.tree.min.js');
        return get_component('sfJqueryTreeDoctrineManager', 'manager', array('model' => $model, 'field' => $field, 'root' => $root));
    }