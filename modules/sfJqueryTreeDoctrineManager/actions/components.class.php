<?php


class sfJqueryTreeDoctrineManagerComponents extends sfComponents
{
  
	public function executeManager(){
		$this->options = $this->getModelOptions();
		$this->hasManyRoots = $this->modelHasManyRoots();
		if ($this->records = $this->executeControl()){
			$request = $this->getRequest();
			
			if (  !$request->hasParameter('root') && !$this->modelHasManyRoots() ){
				$this->getController()->redirect(url_for(  $request->getParameter('module') . '/'. $request->getParameter('action') .'?root=1'), true);
				return sfView::NONE;
			}
			elseif ( !$request->hasParameter('root') && $this->modelHasManyRoots() ){
				$this->roots = $this->getRoots( $this->model );
			}
			else{
				$this->records = $this->getTree($this->model, $request->getParameter('root'));
			}
			
			
			
		}
	}


	private function executeControl(){
		if ( !$this->modelIsNestedSet() ){
			throw new Exception('Model "'.$this->model.'" is not a NestedSet');
			return false;
		}
		return true;		
	}
	
	
	
	private function getRoots($model){
		$tree = Doctrine_Core::getTable($model)->getTree();
    return $tree->fetchRoots();
  }
	
	
	 private function getTree($model, $rootId = 0){
		$tree = Doctrine_Core::getTable($model)->getTree();
    if( $rootId ){
			$root = $tree->fetchRoot($rootId);
			return $root ? $tree->fetchBranch($root->getId()) : false; 
    } else {
      return $tree->fetchTree();
    }
  }
	
	/*
	 * Return the options of the model
	 */
	private function getModelOptions(){
		$model = $this->model;
		$record = new $model;
		return $record->getOptions();
	}
	
	private function modelIsNestedSet(){
		return $this->options['treeImpl'] == 'NestedSet';
	}
	
	private function modelHasManyRoots(){
		return isset($this->options['treeOptions']['hasManyRoots']) && $this->options['treeOptions']['hasManyRoots'];
	}
	
	

}