<?php


class sfJqueryTreeDoctrineManagerComponents extends sfComponents
{
    public function getTree($model, $rootId = 0)
  {
    $tree = Doctrine_Core::getTable($model)->getTree();
    
    if( $rootId )
    {
      $root = $tree->findRoot($rootId);
      return $tree->fetchBranch($root->getId()); 
      
    } else {
      return $tree->fetchTree();
    }
  }

  public function executeManager()
  {
    $this->records = $this->getTree($this->model, $this->root);
    if (!$this->records){ echo 'pas de racine';}
    
    
  }
}