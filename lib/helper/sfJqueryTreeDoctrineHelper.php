<?php
    function get_nested_set_manager($model, $field, $root = 0){
        sfContext::getInstance()->getResponse()->addStylesheet('/sfJqueryTreeDoctrineManagerPlugin/css/screen.css');
        sfContext::getInstance()->getResponse()->addJavascript('/sfJqueryTreeDoctrineManagerPlugin/js/jquery.tree.min.js');
        return get_component('sfJqueryTreeDoctrineManager', 'manager', array('model' => $model, 'field' => $field, 'root' => $root));
    }