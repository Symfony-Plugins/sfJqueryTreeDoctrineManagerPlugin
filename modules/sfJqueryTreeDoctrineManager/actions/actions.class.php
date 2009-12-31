<?php


/**
 * sfJsTreeDoctrine actions.
 * 
 * @package    sfJsTreeDoctrinePlugin
 * @subpackage sfJsTreeDoctrine
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
class sfJqueryTreeDoctrineManagerActions extends sfActions
{



  public function getTree($model, $rootId = 0)
  {
    if( $rootId )
    {
      $root = Doctrine_Core::getTable($model)->getTree()->findRoot($rootId);
    
      return Doctrine_Core::getTable($model)->getTree()->fetchBranch($root->getId()); 
    } else {
      return Doctrine_Core::getTable($model)->getTree()->fetchTree();
    }
  }

  public function executeAdd_child()
  {
    $parent_id = $this->getRequestParameter('parent_id');
    $model = $this->getRequestParameter('model');
    $field = $this->getRequestParameter('field');
    $value = $this->getRequestParameter('value');
    $record = Doctrine_Core::getTable($model)->find($parent_id);

    $child = new $model;
    $child->set($field, $value);
    $record->getNode()->addChild($child);
    
    $this->json = json_encode($child->toArray());
    
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $this->setTemplate('json');
    
    

  }
  
  public function executeAdd_root()
  {
    $model = $this->getRequestParameter('model');
    $data = $this->getRequestParameter( strtolower($model) );
    
    $tree = $this->getTree($model);
    
    $root = new $model;
    
    $root->synchronizeWithArray( $data );    
    
    Doctrine_Core::getTable($model)->getTree()->createRoot($root);
    $this->records = $this->getTree($model);
    return sfView::NONE;
  }

  public function executeEdit_field()
  {
    $id = $this->getRequestParameter('id');
    $model = $this->getRequestParameter('model');
    $field = $this->getRequestParameter('field');
    $value = $this->getRequestParameter('value');

    $record = Doctrine_Core::getTable($model)->find($id);
    $record->set($field, $value);
    $record->save();

    $this->json = json_encode($record->toArray());
    
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $this->setTemplate('json');
  }

  public function executeDelete()
  {
    $id = $this->getRequestParameter('id');
    $model = $this->getRequestParameter('model');
    
    $record = Doctrine_Core::getTable($model)->find($id);
    $record->getNode()->delete();
    $this->json = json_encode(array());
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $this->setTemplate('json');
    
  }

  public function executeMove()
  {
    $id = $this->getRequestParameter('id');
    $to_id = $this->getRequestParameter('to_id');
    $model = $this->getRequestParameter('model');
    $movetype = $this->getRequestParameter('movetype');
    
    $record = Doctrine_Core::getTable($model)->find($id);
    $dest = Doctrine_Core::getTable($model)->find($to_id);
    
    if( $movetype == 'inside' )
    {
      //$prev = $record->getNode()->getPrevSibling();
      $record->getNode()->moveAsLastChildOf($dest);
    }
    else if( $movetype == 'after' )
    {
      $record->getNode()->moveAsNextSiblingOf($dest);
    }
    
    else if( $movetype == 'before' )
    {
      //$next = $record->getNode()->getNextSibling();
      $record->getNode()->moveAsPrevSiblingOf($dest);
    }
    //return sfView::NONE;
  }

 
}
