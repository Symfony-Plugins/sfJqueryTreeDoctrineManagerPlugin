<?php
class sfJqueryTreeDoctrineManager
{
	/**
	 * Returns the roots objects of the $model
	 * 
	 * @param string $model
	 * @return Doctrine_Collection
	 */
	public static function getRoots($model)
	{
		$tree = Doctrine_Core::getTable($model)->getTree();
        return $tree->fetchRoots();
	}
	
	/**
	 * Returns the subobjects of the specified root
	 * 
	 * @param string $model
	 * @param int $rootId
	 * @return Doctrine_Collection
	 */
	public static function getTree($model, $rootId = null)
	{
		$tree = Doctrine_Core::getTable($model)->getTree();
        $options = array();
        if($rootId !== null)
        {
            $options['root_id'] = $rootId;
        }
        return $tree->fetchTree($options);
	}
	
	/**
	 * Return true if the model specified has many roots, else return false
	 * 
	 * @param string $model
	 * @return boolean
	 */
	public static function modelHasManyRoots($model)
	{
		$template = Doctrine_Core::getTable($model)->getTemplate('NestedSet');
        $options = $template->option('treeOptions');
        
        return isset($options['hasManyRoots']) && $options['hasManyRoots'];
	}
}