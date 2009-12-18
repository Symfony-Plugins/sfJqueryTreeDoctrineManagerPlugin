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

    echo '<hr>';
    echo '<pre>' . print_r('--' . $this->records,1) . '</pre>';
    echo '<hr>';
  }
}