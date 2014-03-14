<?php 

//require_once 'EhrlichAndreas/AbstractCms/Exception.php';

//require_once 'EhrlichAndreas/AbstractCms/Cms/Abstract.php';

//require_once 'EhrlichAndreas/Db/Expr.php';

//require_once 'EhrlichAndreas/Db/Delete.php';

//require_once 'EhrlichAndreas/Db/Insert.php';

//require_once 'EhrlichAndreas/Db/Select.php';

//require_once 'EhrlichAndreas/Db/Update.php';

/**
 * Library base exception
 * 
 * @author Ehrlich, Andreas <ehrlich.andreas@googlemail.com>
 */
class EhrlichAndreas_AbstractCms_Module extends EhrlichAndreas_AbstractCms_Cms_Abstract
{
	
	
    /**
     * Constructor
     * 
     * @param array $options
     *            Associative array of options
     * @throws EhrlichAndreas_AbstractCms_Exception
     * @return void
     */
    public function __construct ($options = array())
    {
        $options = $this->_getCmsConfigFromAdapter($options);
        
        if (! isset($options['adapterNamespace']))
        {
            $options['adapterNamespace'] = 'EhrlichAndreas_AbstractCms_Adapter';
        }
		
        if (! isset($options['exceptionclass']))
        {
            $options['exceptionclass'] = 'EhrlichAndreas_AbstractCms_Exception';
        }
        parent::__construct($options);
    }
	
    /**
     *
     * @param string $function
     * @param array $params
     * @param boolean $returnAsString
     * @return mixed
     */
	protected function _add ($function, $params = array(), $returnAsString = false)
	{
		$tableFunc = array
		(
			$this,
			'getTable' . $function,
		);
		
		$fieldsFunc = array
		(
			$this,
			'getFields' . $function,
		);
		
		$keyFieldsFunc = array
		(
			$this,
			'getKeyFields' . $function,
		);
		
		
		$adapter = $this->getAdapter();
		
		$table = call_user_func_array($tableFunc, array());
		
		$fields = call_user_func_array($fieldsFunc, array());
		
		$keyfields = call_user_func_array($keyFieldsFunc, array());

        $query = $this->modelBasic->add($adapter, $table, $params, $fields, $keyfields, true);

        if ($this->switch == 'mysql')
        {
            //$query = 'INSERT IGNORE' . substr($query, 6); // . ';';
        }
        elseif ($this->switch == 'sqlite')
        {
            //$query = 'INSERT OR IGNORE' . substr($query, 6); // . ';';
        }

        if ($returnAsString)
        {
            return $query;
        }

        return $this->modelBasic->insert($adapter, $table, $query);
	}
	
    /**
     *
     * @param string $function
     * @param array $params
     * @param boolean $returnAsString
     * @return mixed
     */
	protected function _delete ($function, $params = array(), $returnAsString = false)
	{
		$tableFunc = array
		(
			$this,
			'getTable' . $function,
		);
		
		$fieldsFunc = array
		(
			$this,
			'getFields' . $function,
		);
		
		$keyFieldsFunc = array
		(
			$this,
			'getKeyFields' . $function,
		);
		
		
		$adapter = $this->getAdapter();
		
		$table = call_user_func_array($tableFunc, array());
		
		$fields = call_user_func_array($fieldsFunc, array());
		
		$keyfields = call_user_func_array($keyFieldsFunc, array());

		
        if ($returnAsString)
        {
            $query = $this->modelBasic->remove($adapter, $table, $params, $fields, $keyfields, true);

            if ($this->switch == 'mysql')
            {
                //$query = $query . ';';
            }
            elseif ($this->switch == 'sqlite')
            {
                //$query = $query . ';';
            }

            return $query;
        }

        return $this->modelBasic->remove($adapter, $table, $params, $fields, $keyfields);
	}
	
    /**
     *
     * @param string $function
     * @param array $params
     * @param boolean $returnAsString
     * @return mixed
     */
	protected function _edit ($function, $params = array(), $returnAsString = false)
	{
		$tableFunc = array
		(
			$this,
			'getTable' . $function,
		);
		
		$fieldsFunc = array
		(
			$this,
			'getFields' . $function,
		);
		
		$keyFieldsFunc = array
		(
			$this,
			'getKeyFields' . $function,
		);
		
		
		$adapter = $this->getAdapter();
		
		$table = call_user_func_array($tableFunc, array());
		
		$fields = call_user_func_array($fieldsFunc, array());
		
		$keyfields = call_user_func_array($keyFieldsFunc, array());

        if ($returnAsString)
        {
            $query = $this->modelBasic->edit($adapter, $table, $params, $fields, $keyfields, true);

            if ($this->switch == 'mysql')
            {
                //$query = $query . ';';
            }
            elseif ($this->switch == 'sqlite')
            {
                //$query = $query . ';';
            }

            return $query;
        }

        return $this->modelBasic->edit($adapter, $table, $params, $fields, $keyfields);
	}
	
    /**
     *
     * @param string $function
     * @param array $params
     * @param boolean $returnAsString
     * @return mixed
     */
	protected function _get ($function, $params = array(), $returnAsString = false)
	{
		$tableFunc = array
		(
			$this,
			'getTable' . $function,
		);
		
		$fieldsFunc = array
		(
			$this,
			'getFields' . $function,
		);
		
		$keyFieldsFunc = array
		(
			$this,
			'getKeyFields' . $function,
		);
		
		
		$adapter = $this->getAdapter();
		
		$table = call_user_func_array($tableFunc, array());
		
		$fields = call_user_func_array($fieldsFunc, array());
		
		$keyfields = call_user_func_array($keyFieldsFunc, array());

		
        if ($returnAsString)
        {
            $query = $this->modelBasic->view($adapter, $table, $params, $fields, $keyfields, true);

            if ($this->switch == 'mysql')
            {
                //$query = $query . ';';
            }
            elseif ($this->switch == 'sqlite')
            {
                //$query = $query . ';';
            }

            return $query;
        }

        return $this->modelBasic->view($adapter, $table, $params, $fields, $keyfields);
	}

    /**
     *
     * @param string $function
     * @param array $where
     * @return array
     */
    public function _getList ($function, $where = array())
    {
        /*
		if (isset($where['where']))
		{
			$where = $where['where'];
		}
         * 
         */
		
		$list = array();

		$count = 500;
		$page = 1;
		
        /*
		$param = array
		(
			'where' => $where
		);
         * 
         */
		
		do
		{
            $param = $where;
            
			$param['page'] = $page;
			$param['count'] = $count;
			
			$rowset = $this->_get($function, $param);
			
			$run = (count($rowset) > 0);
			
			foreach ($rowset as $row)
			{
				$list[] = $row;
			}
			
			$page++;
		}
		while($run);
		
		return $list;
    }
	
	/**
	 * 
	 * @return GoldAg_Db_Abstract
	 */
	public function install ()
	{
		return $this;
	}
}

