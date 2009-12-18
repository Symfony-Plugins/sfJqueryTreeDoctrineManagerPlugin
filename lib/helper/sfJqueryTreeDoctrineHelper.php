<?php
    function get_nested_set_manager($model, $field, $root = 0){
        sfContext::getInstance()->getResponse()->addStylesheet('/sfJqueryTreeDoctrinePlugin/css/screen.css');
        sfContext::getInstance()->getResponse()->addJavascript('/sfJqueryTreeDoctrinePlugin/js/jquery.tree.min.js');
        return get_component('sfJsTreeDoctrine', 'manager', array('model' => $model, 'field' => $field, 'root' => $root));
    }