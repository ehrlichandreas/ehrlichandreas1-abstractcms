<?php 

require_once 'EhrlichAndreas/Db/Exception.php';

require_once 'EhrlichAndreas/Db/Expr.php';

require_once 'EhrlichAndreas/Db/Delete.php';

require_once 'EhrlichAndreas/Db/Insert.php';

require_once 'EhrlichAndreas/Db/Select.php';

require_once 'EhrlichAndreas/Db/Update.php';

require_once 'EhrlichAndreas/Util/Object.php';

/**
 *
 * @author Ehrlich, Andreas <ehrlich.andreas@googlemail.com>
 */
class EhrlichAndreas_AbstractCms_Abstract_Model
{
	
    /**
     * Return the connection resource
     * If we are not connected, the connection is made
     *
     * @param EhrlichAndreas_AbstractCms_Abstract_Adapter_Abstract|EhrlichAndreas_Db_Adapter_Abstract|Zend_Db_Adapter_Abstract $adapter
     * @throws EhrlichAndreas_AbstractCms_Exception
     * @return EhrlichAndreas_Db_Adapter_Abstract Connection resource
	 */
	public static function getConnection($adapter)
	{
		
		if (EhrlichAndreas_Util_Object::isInstanceOf($adapter, 'EhrlichAndreas_Db_Adapter_Abstract'))
		{
			return $adapter;
		}
		
		if (EhrlichAndreas_Util_Object::isInstanceOf($adapter, 'Zend_Db_Adapter_Abstract'))
		{
			return $adapter;
		}
		
		if (EhrlichAndreas_Util_Object::isInstanceOf($adapter, 'EhrlichAndreas_Db_ZF2Bridge_Adapter'))
		{
			return $adapter;
		}
		
		if (EhrlichAndreas_Util_Object::isInstanceOf($adapter, 'Zend\Db\Adapter\Adapter'))
		{
			return new EhrlichAndreas_Db_ZF2Bridge_Adapter($adapter);
		}
		
        return $adapter->getConnection();
	}

    /**
     * Inserts a table row with specified data.
     *
     * @param EhrlichAndreas_AbstractCms_Abstract_Adapter_Abstract|EhrlichAndreas_Db_Adapter_Abstract|Zend_Db_Adapter_Abstract $adapter
     * @param string $table
     * @param array $param
     *            Column-value pairs.
     * @param array $fields
     *            Columns.
     * @param array $keyfields
     *            Key columns.
     * @param string $returnAsString
     *            Return the computed query as string.
     * @return int number of affected rows.
     * @throws Exception
     */
    public function add ($adapter, $table, $params = array(), $fields = array(), $keyfields = array(), $returnAsString = false)
    {
        $param = array();

        if (! empty($fields))
        {
            foreach ($fields as $field)
            {
                if (isset($params[$field]))
                {
                    $param[$field] = $params[$field];
                }
            }
        }

        if (! empty($keyfields))
        {
            foreach ($keyfields as $field)
            {
                if (isset($params[$field]))
                {
                    $param[$field] = $params[$field];
                }
            }
        }

        return $this->insert($adapter, $table, $param, $returnAsString);
    }

    /**
     * Inserts a table row with specified data.
     *
     * @param EhrlichAndreas_AbstractCms_Abstract_Adapter_Abstract|EhrlichAndreas_Db_Adapter_Abstract|Zend_Db_Adapter_Abstract $adapter
     * @param string $table
     * @param array $param
     *            Column-value pairs.
     * @param string $returnAsString
     *            Return the computed query as string.
     * @return int number of affected rows.
     * @throws Exception
     */
    public function insert ($adapter, $table, $params = array(), $returnAsString = false)
    {
        $db = EhrlichAndreas_AbstractCms_Abstract_Model::getConnection($adapter);

        if (is_string($params))
        {
            $query = $params;
        }
        elseif (! EhrlichAndreas_Util_Object::isInstanceOf($params, 'EhrlichAndreas_Db_Insert'))
        {
            $query = new EhrlichAndreas_Db_Insert($db);

            foreach ($params as $key => $value)
            {
                $escape = true;

                if (is_array($value))
                {
                    if (array_key_exists('escape', $value) && ! is_null($value['escape']))
                    {
                        $escape = $value['escape'];
                    }
                    elseif (count($value) == 2 && array_key_exists(0, $value) && array_key_exists(1, $value) && ! is_null($value[1]) && is_bool($value[1]))
                    {
                        $escape = $value[1];
                    }

                    if (isset($value['value']))
                    {
                        $value = $value['value'];
                    }
                    elseif (array_key_exists(0, $value))
                    {
                        $value = $value[0];
                    }
                    else
                    {
                        $value = array_shift($value);
                    }
                }
                elseif (is_object($value) && method_exists($value, '__toString')/* && ! method_exists($value, 'assemble')*/)
                {
                    $value = $value->__toString();

                    $escape = false;
                }

                $escape = (bool) intval($escape);

                if (! $escape)
                {
                    $value = new EhrlichAndreas_Db_Expr($value);
                }

                $query->insert($key, $value);
            }
        }
        else
        {
            $query = $params;
        }

        if (! is_string($query))
        {
            $query->into($table);

            $query = $query->assemble();
        }

        if ($returnAsString)
        {
            return $query;
        }

        $stmt = $db->query($query);
        
        $return = $db->lastInsertId($table);
        
        $stmt->closeCursor();

        return $return;
    }

    /**
     * Deletes table rows based on a WHERE clause.
     *
     * @param EhrlichAndreas_AbstractCms_Abstract_Adapter_Abstract|EhrlichAndreas_Db_Adapter_Abstract|Zend_Db_Adapter_Abstract $adapter
     * @param string $table
     * @param array $params
     *            Column-value pairs.
     * @param array $fields
     *            Columns.
     * @param array $keyfields
     *            Key columns.
     * @param string $returnAsString
     *            Return the computed query as string.
     * @return int number of affected rows.
     * @throws Exception
     */
    public function remove ($adapter, $table, $params = array(), $fields = array(), $keyfields = array(), $returnAsString = false)
    {
        $limit = null;

        $where = null;

        if (isset($params['limit']))
        {
            $limit = $params['limit'];
        }

        if (! is_numeric($limit) && empty($limit))
        {
            $limit = null;
        }

        if (isset($params['where']))
        {
            $where = $params['where'];
        }

        if (empty($where))
        {
            $where = array();

            if (! empty($fields))
            {
                foreach ($fields as $field)
                {
                    if (isset($params[$field]))
                    {
                        $where[$field] = $params[$field];
                    }
                }
            }

            if (! empty($keyfields))
            {
                foreach ($keyfields as $field)
                {
                    if (isset($params[$field]))
                    {
                        $where[$field] = $params[$field];
                    }
                }
            }
        }

        if (empty($where))
        {
            $where = null;
        }

        return $this->delete($adapter, $table, $where, $limit, $returnAsString);
    }

    /**
     * Deletes table rows based on a WHERE clause.
     *
     * @param EhrlichAndreas_AbstractCms_Abstract_Adapter_Abstract|EhrlichAndreas_Db_Adapter_Abstract|Zend_Db_Adapter_Abstract $adapter
     * @param string $table
     * @param array|string $where
     * @param int $limit
     * @param string $returnAsString
     *            Return the computed query as string.
     * @return int
     * @throws Exception
     */
    public function delete ($adapter, $table, $where = NULL, $limit = NULL, $returnAsString = false)
    {
        $db = EhrlichAndreas_AbstractCms_Abstract_Model::getConnection($adapter);

        if (! EhrlichAndreas_Util_Object::isInstanceOf($where, 'EhrlichAndreas_Db_Delete'))
        {
            $query = new EhrlichAndreas_Db_Delete($db);

            if ($where !== null)
            {

                $where = (array) $where;

                foreach ($where as $key => $value)
                {
                    $escape = true;

                    if (is_array($value))
                    {
                        if (array_key_exists('escape', $value) && ! is_null($value['escape']))
                        {
                            $escape = $value['escape'];
                        }
                        elseif (count($value) == 2 && array_key_exists(0, $value) && array_key_exists(1, $value) && ! is_null($value[1]) && is_bool($value[1]))
                        {
                            $escape = $value[1];
                        }

                        if (array_key_exists('value', $value))
                        {
                            $value = $value['value'];
                        }
                        elseif (count($value) == 2 && array_key_exists(0, $value) && array_key_exists(1, $value) && ! is_null($value[1]) && is_bool($value[1]))
                        {
                            $value = $value[0];
                        }
                        else
                        {
                            //$value = array_shift($value);
                        }
                    }
                    elseif (is_object($value) && method_exists($value, '__toString')/* && ! method_exists($value, 'assemble')*/)
                    {
                        $value = $value->__toString();

                        $escape = false;
                    }

                    $escape = (bool) intval($escape);

                    if (! $escape)
                    {
                        $value = new EhrlichAndreas_Db_Expr($value);
                    }

                    if (is_int($key))
                    {
                        // $value is the full condition
                        $query->where($value);
                    }
                    elseif (! is_array($value))
                    {
                        // $key is the condition with placeholder,
                        // and $val is quoted into the condition
                        $query->where($db->quoteIdentifier($key) . ' = ?', $value);
                    }
                    else
                    {
                        // $key is the condition with placeholder,
                        // and $val is array quoted into the condition
                        $query->where($db->quoteIdentifier($key) . ' in (?)', $value);
                    }
                }
            }
        }
        else
        {
            $query = $where;
        }

        $query->from($table);

        $query = $query->assemble();

        if ($returnAsString)
        {
            return $query;
        }

        $stmt = $db->query($query);
        
        $return = $stmt->rowCount();
        
        $stmt->closeCursor();

        return $return;
    }

    /**
     * Updates table rows with specified data based on a WHERE clause.
     *
     * @param EhrlichAndreas_AbstractCms_Abstract_Adapter_Abstract|EhrlichAndreas_Db_Adapter_Abstract|Zend_Db_Adapter_Abstract $adapter
     * @param string $table
     * @param array $params
     *            Column-value pairs.
     * @param array $fields
     *            Columns.
     * @param array $keyfields
     *            Key columns.
     * @param string $returnAsString
     *            Return the computed query as string.
     * @return int number of affected rows.
     * @throws Exception
     */
    public function edit ($adapter, $table, $params = array(), $fields = array(), $keyfields = array(), $returnAsString = false)
    {
        $param = array();

        $limit = null;

        $where = null;

        if (isset($params['limit']))
        {
            $limit = $params['limit'];
        }

        if (! is_numeric($limit) && empty($limit))
        {
            $limit = null;
        }

        if (isset($params['where']))
        {
            $where = $params['where'];
        }

        if (! empty($fields))
        {
            foreach ($fields as $field)
            {
                if (isset($params[$field]))
                {
                    $param[$field] = $params[$field];
                }
            }
        }

        if (empty($where))
        {
            $where = array();

            if (! empty($keyfields))
            {
                foreach ($keyfields as $field)
                {
                    if (isset($params[$field]))
                    {
                        $where[$field] = $params[$field];
                    }
                }
            }
        }

        if (empty($where))
        {
            $where = null;
        }

        return $this->update($adapter, $table, $param, $where, $limit, $returnAsString);
    }

    /**
     * Updates table rows with specified data based on a WHERE clause.
     *
     * @param EhrlichAndreas_AbstractCms_Abstract_Adapter_Abstract|EhrlichAndreas_Db_Adapter_Abstract|Zend_Db_Adapter_Abstract $adapter
     * @param string $table
     * @param array $params
     * @param array|string $where
     * @param int $limit
     * @param string $returnAsString
     *            Return the computed query as string.
     * @return int
     * @throws Exception
     */
    public function update ($adapter, $table, $params, $where = NULL, $limit = NULL, $returnAsString = false)
    {
        if (empty($params))
        {
            return 0;
        }
		
        $db = EhrlichAndreas_AbstractCms_Abstract_Model::getConnection($adapter);

        if (! EhrlichAndreas_Util_Object::isInstanceOf($where, 'EhrlichAndreas_Db_Update'))
        {
            $query = new EhrlichAndreas_Db_Update($db);

            foreach ($params as $key => $value)
            {
                $escape = true;

                if (is_array($value))
                {
                    if (array_key_exists('escape', $value) && ! is_null($value['escape']))
                    {
                        $escape = $value['escape'];
                    }
                    elseif (count($value) == 2 && array_key_exists(0, $value) && array_key_exists(1, $value) && ! is_null($value[1]) && is_bool($value[1]))
                    {
                        $escape = $value[1];
                    }

                    if (array_key_exists('value', $value))
                    {
                        $value = $value['value'];
                    }
                    elseif (array_key_exists(0, $value))
                    {
                        $value = $value[0];
                    }
                    else
                    {
                        $value = array_shift($value);
                    }
                }
                elseif (is_object($value) && method_exists($value, '__toString')/* && ! method_exists($value, 'assemble')*/)
                {
                    $value = $value->__toString();

                    $escape = false;
                }

                $escape = (bool) intval($escape);

                if (! $escape)
                {
                    $value = new EhrlichAndreas_Db_Expr($value);
                }

                $query->set($key, $value);
            }

            if ($where !== null)
            {

                $where = (array) $where;

                foreach ($where as $key => $value)
                {
                    $escape = true;

                    if (is_array($value))
                    {
                        if (array_key_exists('escape', $value) && ! is_null($value['escape']))
                        {
                            $escape = $value['escape'];
                        }
                        elseif (count($value) == 2 && array_key_exists(0, $value) && array_key_exists(1, $value) && ! is_null($value[1]) && is_bool($value[1]))
                        {
                            $escape = $value[1];
                        }

                        if (array_key_exists('value', $value))
                        {
                            $value = $value['value'];
                        }
                        elseif (count($value) == 2 && array_key_exists(0, $value) && array_key_exists(1, $value) && ! is_null($value[1]) && is_bool($value[1]))
                        {
                            $value = $value[0];
                        }
                        else
                        {
                            //$value = array_shift($value);
                        }
                    }
                    elseif (is_object($value) && method_exists($value, '__toString')/* && ! method_exists($value, 'assemble')*/) {
                        $value = $value->__toString();

                        $escape = false;
                    }

                    $escape = (bool) intval($escape);

                    if (! $escape)
                    {
                        $value = new EhrlichAndreas_Db_Expr($value);
                    }

                    if (is_int($key))
                    {
                        // $value is the full condition
                        $query->where($value);
                    }
                    elseif (! is_array($value))
                    {
                        // $key is the condition with placeholder,
                        // and $val is quoted into the condition
                        $query->where($db->quoteIdentifier($key) . ' = ?', $value);
                    }
                    else
                    {
                        // $key is the condition with placeholder,
                        // and $val is array quoted into the condition
                        $query->where($db->quoteIdentifier($key) . ' in (?)', $value);
                    }
                }
            }
        }
        else
        {
            $query = $where;
        }

        $query->update($table);

        $query = $query->assemble();

        if ($returnAsString)
        {
            return $query;
        }

        $stmt = $db->query($query);
        
        $return = $stmt->rowCount();
        
        $stmt->closeCursor();

        return $return;
    }

    /**
     * Fetches all result rows as a sequential array.
     *
     * @param EhrlichAndreas_AbstractCms_Abstract_Adapter_Abstract|EhrlichAndreas_Db_Adapter_Abstract|Zend_Db_Adapter_Abstract $adapter
     * @param string $table
     * @param array $param
     *            Column-value pairs.
     * @param array $fields
     *            Columns.
     * @param array $keyfields
     *            Key columns.
     * @param string $returnAsString
     *            Return the computed query as string.
     * @return array The row results per assoc array fetch mode.
     * @throws Exception
     */
    public function view ($adapter, $table, $param = array(), $fields = array(), $keyfields = array(), $returnAsString = false)
    {
        $where = null;
        
        $cols = null;
        
        $order = null;
        
        $group = null;
        
        $having = null;
        
        $count = null;
        
        $offset = null;

        if (isset($param['where']))
        {
            $where = $param['where'];
        }
        
        if (isset($param['cols']))
        {
            $cols = $param['cols'];
        }
        
        if (isset($param['order']))
        {
            $order = $param['order'];
        }
        
        if (isset($param['group']))
        {
            $group = $param['group'];
        }
        
        if (isset($param['having']))
        {
            $having = $param['having'];
        }
        
        if (isset($param['count']))
        {
            $count = $param['count'];
        }
        
        if (is_null($count) && isset($param['limit']))
        {
            $count = $param['limit'];
        }
        
        if (isset($param['offset']))
        {
            $offset = $param['offset'];
        }
        
        if (is_null($offset) && !is_null($count) && isset($param['page']))
        {
            $offset = $count * ($param['page'] - 1);
        }

        if (empty($where))
        {
            $where = array();

            if (! empty($fields))
            {
                foreach ($fields as $field)
                {
                    if (isset($param[$field]))
                    {
                        $where[$field] = $param[$field];
                    }
                }
            }

            if (! empty($keyfields))
            {
                foreach ($keyfields as $field)
                {
                    if (isset($param[$field]))
                    {
                        $where[$field] = $param[$field];
                    }
                }
            }
        }

        if (empty($where))
        {
            $where = null;
        }

        if (empty($cols))
        {
            $cols = $fields;
        }

        return $this->fetchAll($adapter, $table, $where, $cols, $order, $group, $having, $count, $offset, $returnAsString);
    }

    /**
     * Fetches all result rows as a sequential array.
     *
     * @param EhrlichAndreas_AbstractCms_Abstract_Adapter_Abstract|EhrlichAndreas_Db_Adapter_Abstract|Zend_Db_Adapter_Abstract $adapter
     * @param string $table
     * @param string|array $where
     *            OPTIONAL An SQL WHERE clause.
     * @param string|array $cols
     *            OPTIONAL The columns to select from the joined table.
     * @param string|array $order
     *            OPTIONAL The column(s) and direction to order by.
     * @param string|array $group
     *            OPTIONAL The column(s) to group by.
     * @param string|array $having
     *            OPTIONAL The HAVING condition.
     * @param int $count
     *            OPTIONAL An SQL LIMIT count.
     * @param int $offset
     *            OPTIONAL An SQL LIMIT offset.
     * @param string $returnAsString
     *            Return the computed query as string.
     * @return array The row results per assoc array fetch mode.
     * @throws Exception
     */
    public function fetchAll ($adapter, $table, $where = null, $cols = null, $order = null, $group = null, $having = null, $count = null, $offset = null, $returnAsString = false)
    {
        $db = EhrlichAndreas_AbstractCms_Abstract_Model::getConnection($adapter);

        if (! EhrlichAndreas_Util_Object::isInstanceOf($where, 'EhrlichAndreas_Db_Select'))
        {
            $query = new EhrlichAndreas_Db_Select($db);

            $cols = ($cols == null) ? '*' : $cols;

            if (! is_array($cols))
            {
                $cols = array
                (
                    $cols,
                );
            }

            foreach ($cols as $key => $value)
            {
                $escape = true;

                if (is_array($value))
                {
                    if (array_key_exists('escape', $value) && ! is_null($value['escape']))
                    {
                        $escape = $value['escape'];
                    }
                    elseif (count($value) == 2 && array_key_exists(0, $value) && array_key_exists(1, $value) && ! is_null($value[1]) && is_bool($value[1]))
                    {
                        $escape = $value[1];
                    }

                    if (array_key_exists('value', $value))
                    {
                        $value = $value['value'];
                    }
                    elseif (array_key_exists(0, $value))
                    {
                        $value = $value[0];
                    }
                    else
                    {
                        $value = array_shift($value);
                    }
                }
                elseif (is_object($value) && method_exists($value, '__toString')/* && ! method_exists($value, 'assemble')*/) {
                    $value = $value->__toString();

                    $escape = false;
                }

                $escape = (bool) intval($escape);

                if (! $escape)
                {
                    $value = new EhrlichAndreas_Db_Expr($value);
                }

                $cols[$key] = $value;
            }

            $query->from($table, $cols);

            if ($where !== null)
            {

                $where = (array) $where;

                foreach ($where as $key => $value)
                {
                    $escape = true;

                    if (is_array($value))
                    {
                        if (array_key_exists('escape', $value) && ! is_null($value['escape']))
                        {
                            $escape = $value['escape'];
                        }
                        elseif (count($value) == 2 && array_key_exists(0, $value) && array_key_exists(1, $value) && ! is_null($value[1]) && is_bool($value[1]))
                        {
                            $escape = $value[1];
                        }

                        if (array_key_exists('value', $value))
                        {
                            $value = $value['value'];
                        }
                        elseif (count($value) == 2 && array_key_exists(0, $value) && array_key_exists(1, $value) && ! is_null($value[1]) && is_bool($value[1]))
                        {
                            $value = $value[0];
                        }
                        else
                        {
                            //$value = array_shift($value);
                        }
                    }
                    elseif (is_object($value) && method_exists($value, '__toString')/* && ! method_exists($value, 'assemble')*/) {
                        $value = $value->__toString();

                        $escape = false;
                    }

                    $escape = (bool) intval($escape);

                    if (! $escape)
                    {
                        $value = new EhrlichAndreas_Db_Expr($value);
                    }

                    if (is_int($key))
                    {
                        // $value is the full condition
                        $query->where($value);
                    }
                    elseif (! is_array($value))
                    {
                        // $key is the condition with placeholder,
                        // and $val is quoted into the condition
                        $query->where($db->quoteIdentifier($key) . ' = ?', $value);
                    }
                    else
                    {
                        // $key is the condition with placeholder,
                        // and $val is array quoted into the condition
                        $query->where($db->quoteIdentifier($key) . ' in (?)', $value);
                    }
                }
            }

            if ($order !== null)
            {
                if (is_string($order))
                {
                    $order = explode(',', $order);
                }

                if (! is_array($order))
                {
                    $order = array
                    (
                        $order,
                    );
                }

                foreach ($order as $val)
                {
                    $query->order($val);
                }
            }

            if ($group !== null)
            {
                if (is_string($group))
                {
                    $group = explode(',', $group);
                }

                if (! is_array($group))
                {
                    $group = array
                    (
                        $group,
                    );
                }

                foreach ($group as $val)
                {
                    $query->group($val);
                }
            }

            if ($having !== null)
            {
                if (! is_array($having))
                {
                    $having = array
                    (
                        $having,
                    );
                }

                foreach ($having as $val)
                {
                    $query->having($val);
                }
            }

            if ($count !== null || $offset !== null)
            {
                $query->limit($count, $offset);
            }
        }
        else
        {
            $query = $where;
        }

        $query = $query->assemble();

        if ($returnAsString)
        {
            return $query;
        }

        $fetchMode = $db->getFetchMode();

        $stmt = $db->query($query);
        
        $return = $stmt->fetchAll($fetchMode);
        
        $stmt->closeCursor();

        // $return = $db->fetchAll($query);

        return $return;
    }
}

?>