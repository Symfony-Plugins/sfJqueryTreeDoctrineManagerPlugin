<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfJqueryTreeDoctrineManager components.
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Gregory Schurgast <michkinn@gmail.com>
 * @author     Gordon Franke <info@nevalon.de>
 * @version    SVN: $Id: BasesfGuardAuthActions.class.php 7745 2008-03-05 11:05:33Z fabien $
 */
class BasesfJqueryTreeDoctrineManagerComponents extends sfComponents
{
  public function getTree($model, $rootId = null)
  {
    $tree = Doctrine_Core::getTable($model)->getTree();

    $options = array();
    if($rootId !== null)
    {
      $options['root_id'] = $rootId;
    }

    return $tree->fetchTree($options);
  }

  public function executeManager()
  {
    $this->records = $this->getTree($this->model, $this->root);

    if (!$this->records){ echo 'pas de racine';}
  }
}
