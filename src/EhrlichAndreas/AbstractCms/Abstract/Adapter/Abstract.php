<?php 

//require_once 'EhrlichAndreas/AbstractCms/Exception.php';

//require_once 'EhrlichAndreas/Db/Adapter/Abstract.php';

//require_once 'EhrlichAndreas/Util/Array.php';

//require_once 'EhrlichAndreas/Util/Object.php';

/**
 *
 * @author Ehrlich, Andreas <ehrlich.andreas@googlemail.com>
 */
class EhrlichAndreas_AbstractCms_Abstract_Adapter_Abstract
{

    /**
     * DB ressource
     *
     * @var EhrlichAndreas_Db_Adapter_Abstract $db
     */
    private $db = null;

    /**
     * Available options
     * =====> (string) db :
     * =====> (string) dbtableprefix :
     * =====> (string) dbtableprefixlength :
     * =====> (string) dbtableprefixlengthmax :
     * =====> (string) dbtableprefixoffset :
     * =====> (string) dbtablesuffix :
     * =====> (string) dbtablesuffixlength :
     * =====> (string) dbtablesuffixlengthmax :
     * =====> (string) dbtablesuffixoffset :
     *
     * @var array $options Available options
     */
    private $options;

    /**
     * Constructor
     *
     * @param array $options
     *            Associative array of options
     *            =====> (string) db :
     *            =====> (string) dbtableprefix :
     *            =====> (string) dbtableprefixlength :
     *            =====> (string) dbtableprefixlengthmax :
     *            =====> (string) dbtableprefixoffset :
     *            =====> (string) dbtablesuffix :
     *            =====> (string) dbtablesuffixlength :
     *            =====> (string) dbtablesuffixlengthmax :
     *            =====> (string) dbtablesuffixoffset :
     * @throws EhrlichAndreas_AbstractCms_Exception
     * @return void
     */
    public function __construct (array $options = array())
    {
        if (empty($options['exceptionclass']))
        {
            $options['exceptionclass'] = 'EhrlichAndreas_AbstractCms_Exception';
        }

        if (empty($options['db']) && empty($options['dbconfig']))
        {
            $this->throwException('db or dbconfig option has to set', $options['exceptionclass']);
        }

        if (! empty($options['db']) && ! EhrlichAndreas_Util_Object::isInstanceOf($options['db'], 'EhrlichAndreas_Db_Adapter_Abstract') && ! EhrlichAndreas_Util_Object::isInstanceOf($options['db'], 'Zend_Db_Adapter_Abstract'))
        {
            $this->throwException("Impossible to open " . $options['db'] . " DB", $options['exceptionclass']);
        }
        elseif (isset($options['dbconfig']))
        {
            $adapter = EhrlichAndreas_Db_Db::getAdapterName($options['dbconfig']);

            if (! EhrlichAndreas_Util_Object::isInstanceOf($adapter, 'EhrlichAndreas_Db_Adapter_Abstract') && ! EhrlichAndreas_Util_Object::isInstanceOf($adapter, 'Zend_Db_Adapter_Abstract'))
            {
                $this->throwException("Impossible to open " . $adapter . " DB", $options['exceptionclass']);
            }
        }
        else
        {
            // $this->throwException("Impossible to open
            // DB",$options['exceptionclass']);
        }

        if (empty($options['db']))
        {
            $options['db'] = null;
        }

        $this->db = $options['db'];
        
        $this->options = $options;

        if (isset($options['install']) && $options['install'])
        {
            $this->install();
        }

        if (isset($options['dbconfig']['install']) && $options['dbconfig']['install'])
        {
            $this->install();
        }

        if (isset($options['params']['install']) && $options['params']['install'])
        {
            $this->install();
        }
    }

    /**
     *
     * @param string $key
     * @return string dbtableprefix
     */
    public function getTablePrefix ($key = null)
    {
        $key2 = 'dbtableprefix';

        if (is_array($this->options) && array_key_exists($key2, $this->options))
        {
            if (null === $key)
            {
                return $this->options[$key2];
            }
            elseif (is_array($this->options[$key2]) && array_key_exists($key, $this->options[$key2]))
            {
                return $this->options[$key2][$key];
            }
            elseif (! is_array($this->options[$key2]))
            {
                return $this->options[$key2];
            }
        }

        return '';
    }

    /**
     *
     * @param string $key
     * @return string dbtableprefixlength
     */
    public function getTablePrefixLength ($key = null)
    {
        $key2 = 'dbtableprefixlength';

        if (is_array($this->options) && array_key_exists($key2, $this->options))
        {
            if (null === $key)
            {
                return $this->options[$key2];
            }
            elseif (is_array($this->options[$key2]) && array_key_exists($key, $this->options[$key2]))
            {
                return $this->options[$key2][$key];
            }
            elseif (! is_array($this->options[$key2]))
            {
                return $this->options[$key2];
            }
        }

        return 0;
    }

    /**
     *
     * @param string $key
     * @return string dbtableprefixlengthmax
     */
    public function getTablePrefixLengthMax ($key = null)
    {
        $key2 = 'dbtableprefixlengthmax';

        if (is_array($this->options) && array_key_exists($key2, $this->options))
        {
            if (null === $key)
            {
                return $this->options[$key2];
            }
            elseif (is_array($this->options[$key2]) && array_key_exists($key, $this->options[$key2]))
            {
                return $this->options[$key2][$key];
            }
            elseif (! is_array($this->options[$key2]))
            {
                return $this->options[$key2];
            }
        }

        return $this->getTablePrefixLength($key);
    }

    /**
     *
     * @param string $key
     * @return string dbtableprefixoffset
     */
    public function getTablePrefixOffset ($key = null)
    {
        $key2 = 'dbtableprefixoffset';

        if (is_array($this->options) && array_key_exists($key2, $this->options))
        {
            if (null === $key)
            {
                return $this->options[$key2];
            }
            elseif (is_array($this->options[$key2]) && array_key_exists($key, $this->options[$key2]))
            {
                return $this->options[$key2][$key];
            }
            elseif (! is_array($this->options[$key2]))
            {
                return $this->options[$key2];
            }
        }

        return 0;
    }

    /**
     *
     * @param string $key
     * @return string dbtablesuffix
     */
    public function getTableSuffix ($key = null)
    {
        $key2 = 'dbtablesuffix';

        if (is_array($this->options) && array_key_exists($key2, $this->options))
        {
            if (null === $key)
            {
                return $this->options[$key2];
            }
            elseif (is_array($this->options[$key2]) && array_key_exists($key, $this->options[$key2]))
            {
                return $this->options[$key2][$key];
            }
            elseif (! is_array($this->options[$key2]))
            {
                return $this->options[$key2];
            }
        }

        return '';
    }

    /**
     *
     * @param string $key
     * @param string $value
     */
    public function setTableSuffix ($key = null, $value = null)
    {
        $key2 = 'dbtablesuffix';

        if (null === $key && null === $value)
        {
            unset($this->options[$key2]);
        }
        elseif (null === $key)
        {
            $this->options[$key2] = $value;
        }
        elseif (null === $value)
        {
            unset($this->options[$key2][$key]);
        }
        else
        {
            $this->options[$key2][$key] = $value;
        }
    }

    /**
     *
     * @param string $key
     * @return string dbtablesuffixlength
     */
    public function getTableSuffixLength ($key = null)
    {
        $key2 = 'dbtablesuffixlength';

        if (is_array($this->options) && array_key_exists($key2, $this->options))
        {
            if (null === $key)
            {
                return $this->options[$key2];
            }
            elseif (is_array($this->options[$key2]) && array_key_exists($key, $this->options[$key2]))
            {
                return $this->options[$key2][$key];
            }
            elseif (! is_array($this->options[$key2]))
            {
                return $this->options[$key2];
            }
        }

        return 0;
    }

    /**
     *
     * @param string $key
     * @return string dbtablesuffixlengthmax
     */
    public function getTableSuffixLengthMax ($key = null)
    {
        $key2 = 'dbtablesuffixlengthmax';

        if (is_array($this->options) && array_key_exists($key2, $this->options))
        {
            if (null === $key)
            {
                return $this->options[$key2];
            }
            elseif (is_array($this->options[$key2]) && array_key_exists($key, $this->options[$key2]))
            {
                return $this->options[$key2][$key];
            }
            elseif (! is_array($this->options[$key2]))
            {
                return $this->options[$key2];
            }
        }

        return $this->getTableSuffixLength($key);
    }

    /**
     *
     * @param string $key
     * @return string dbtablesuffixoffset
     */
    public function getTableSuffixOffset ($key = null)
    {
        $key2 = 'dbtablesuffixoffset';

        if (is_array($this->options) && array_key_exists($key2, $this->options))
        {
            if (null === $key)
            {
                return $this->options[$key2];
            }
            elseif (is_array($this->options[$key2]) && array_key_exists($key, $this->options[$key2]))
            {
                return $this->options[$key2][$key];
            }
            elseif (! is_array($this->options[$key2]))
            {
                return $this->options[$key2];
            }
        }

        return 0;
    }

    /**
     * 
     * @staticvar array $names
     * @staticvar array $_prefix
     * @staticvar array $_prefixLength
     * @staticvar array $_prefixLengthMax
     * @staticvar array $_prefixOffset
     * @staticvar array $_suffix
     * @staticvar array $_suffixLength
     * @staticvar array $_suffixLengthMax
     * @staticvar array $_suffixOffset
     * @param type $table
     * @param int $position
     * @return string
     */
    public function getTableName ($table = '', $position = 0)
    {
        static $names = array();

        static $_prefix = array();
        
        static $_prefixLength = array();
        
        static $_prefixLengthMax = array();
        
        static $_prefixOffset = array();
        
        static $_suffix = array();
        
        static $_suffixLength = array();
        
        static $_suffixLengthMax = array();
        
        static $_suffixOffset = array();

        if ($position < 0)
        {
            $position = 0;
        }

        if (! array_key_exists($table, $_prefix))
        {
            $prefix = $this->getTablePrefix($table);

            $_prefix[$table] = $prefix;
        }
        else
        {
            $prefix = $_prefix[$table];
        }

        if (! array_key_exists($table, $_prefixLength))
        {
            $prefixLength = $this->getTablePrefixLength($table);

            if ($prefixLength < 0)
            {
                $prefixLength = 0;
            }

            $_prefixLength[$table] = $prefixLength;
        }
        else
        {
            $prefixLength = $_prefixLength[$table];
        }

        if (! array_key_exists($table, $_prefixLengthMax))
        {
            $prefixLengthMax = $this->getTablePrefixLengthMax($table);

            if ($prefixLengthMax < $prefixLength)
            {
                $prefixLengthMax = $prefixLength;
            }

            $_prefixLengthMax[$table] = $prefixLengthMax;
        }
        else
        {
            $prefixLengthMax = $_prefixLengthMax[$table];
        }

        if (! array_key_exists($table, $_prefixOffset))
        {
            $prefixOffset = $this->getTablePrefixOffset($table);

            if ($prefixOffset < 0)
            {
                $prefixOffset = 0;
            }

            $_prefixOffset[$table] = $prefixOffset;
        }
        else
        {
            $prefixOffset = $_prefixOffset[$table];
        }

        if (! array_key_exists($table, $_suffix))
        {
            $suffix = $this->getTableSuffix($table);

            $_suffix[$table] = $suffix;
        }
        else
        {
            $suffix = $_suffix[$table];
        }

        if (! array_key_exists($table, $_suffixLength))
        {
            $suffixLength = $this->getTableSuffixLength($table);

            if ($suffixLength < 0)
            {
                $suffixLength = 0;
            }

            $_suffixLength[$table] = $suffixLength;
        }
        else
        {
            $suffixLength = $_suffixLength[$table];
        }

        if (! array_key_exists($table, $_suffixLengthMax))
        {
            $suffixLengthMax = $this->getTableSuffixLengthMax($table);

            if ($suffixLengthMax < $suffixLength)
            {
                $suffixLengthMax = $suffixLength;
            }

            $_suffixLengthMax[$table] = $suffixLengthMax;
        }
        else
        {
            $suffixLengthMax = $_suffixLengthMax[$table];
        }

        if (! array_key_exists($table, $_suffixOffset))
        {
            $suffixOffset = $this->getTableSuffixOffset($table);

            if ($suffixOffset < 0)
            {
                $suffixOffset = 0;
            }

            $_suffixOffset[$table] = $suffixOffset;
        }
        else
        {
            $suffixOffset = $_suffixOffset[$table];
        }

        $varPrefix = '';

        if ($prefixLength > 0)
        {
            $varPrefix = dechex($position);
            
            $varPrefix = str_repeat('0', $prefixLength - strlen($varPrefix)) . $varPrefix;
            
            $varPrefix = str_repeat('0', $prefixLengthMax - $prefixLength) . $varPrefix;
        }

        $varSuffix = '';

        if ($suffixLength > 0)
        {
            $varSuffix = dechex($position);
            
            $varSuffix = str_repeat('0', $suffixLength - strlen($varSuffix)) . $varSuffix;
            
            $varSuffix = str_repeat('0', $suffixLengthMax - $suffixLength) . $varSuffix;
        }

        $index = $table;
        
        $index .= '-' . $varPrefix . '-' . $prefix . '-' . $prefixLength . '-' . $prefixLengthMax . '-' . $prefixOffset;
        
        $index .= '-' . $varSuffix . '-' . $suffix . '-' . $suffixLength . '-' . $suffixLengthMax . '-' . $suffixOffset;

        if (isset($names[$index]))
        {
            return $names[$index];
        }

        $varTable = '';

        if (strlen($prefix) > 0)
        {
            if (strlen($varTable) > 0)
            {
                $varTable = $varTable . '_' . $prefix;
            }
            else
            {
                $varTable = $prefix;
            }
        }

        if (strlen($varPrefix) > 0)
        {
            if (strlen($varTable) > 0)
            {
                $varTable = $varTable . '_' . $varPrefix;
            }
            else
            {
                $varTable = $varPrefix;
            }
        }

        if (strlen($varTable) > 0)
        {
            $varTable = $varTable . '_' . $table;
        }
        else
        {
            $varTable = $table;
        }

        if (strlen($suffix) > 0)
        {
            if (strlen($varTable) > 0)
            {
                $varTable = $varTable . '_' . $suffix;
            }
            else
            {
                $varTable = $suffix;
            }
        }

        if (strlen($varSuffix) > 0)
        {
            if (strlen($varTable) > 0)
            {
                $varTable = $varTable . '_' . $varSuffix;
            }
            else
            {
                $varTable = $varSuffix;
            }
        }

        $names[$index] = $varTable;

        return $varTable;
    }

    /**
     * 
     * @staticvar array $names
     * @staticvar array $_prefix
     * @staticvar array $_prefixLength
     * @staticvar array $_prefixLengthMax
     * @staticvar array $_prefixOffset
     * @staticvar array $_suffix
     * @staticvar array $_suffixLength
     * @staticvar array $_suffixLengthMax
     * @staticvar array $_suffixOffset
     * @param type $table
     * @param type $key
     * @return string
     */
    public function getTableNameMd5 ($table = '', $key = '')
    {
        static $names = array();

        static $_prefix = array();
        
        static $_prefixLength = array();
        
        static $_prefixLengthMax = array();
        
        static $_prefixOffset = array();
        
        static $_suffix = array();
        
        static $_suffixLength = array();
        
        static $_suffixLengthMax = array();
        
        static $_suffixOffset = array();

        if (! array_key_exists($table, $_prefix))
        {
            $prefix = $this->getTablePrefix($table);

            $_prefix[$table] = $prefix;
        }
        else
        {
            $prefix = $_prefix[$table];
        }

        if (! array_key_exists($table, $_prefixLength))
        {
            $prefixLength = $this->getTablePrefixLength($table);

            if ($prefixLength < 0)
            {
                $prefixLength = 0;
            }

            $_prefixLength[$table] = $prefixLength;
        }
        else
        {
            $prefixLength = $_prefixLength[$table];
        }

        if (! array_key_exists($table, $_prefixLengthMax))
        {
            $prefixLengthMax = $this->getTablePrefixLengthMax($table);

            if ($prefixLengthMax < $prefixLength)
            {
                $prefixLengthMax = $prefixLength;
            }

            $_prefixLengthMax[$table] = $prefixLengthMax;
        }
        else
        {
            $prefixLengthMax = $_prefixLengthMax[$table];
        }

        if (! array_key_exists($table, $_prefixOffset))
        {
            $prefixOffset = $this->getTablePrefixOffset($table);

            if ($prefixOffset < 0)
            {
                $prefixOffset = 0;
            }

            $_prefixOffset[$table] = $prefixOffset;
        }
        else
        {
            $prefixOffset = $_prefixOffset[$table];
        }

        if (! array_key_exists($table, $_suffix))
        {
            $suffix = $this->getTableSuffix($table);

            $_suffix[$table] = $suffix;
        }
        else
        {
            $suffix = $_suffix[$table];
        }

        if (! array_key_exists($table, $_suffixLength))
        {
            $suffixLength = $this->getTableSuffixLength($table);

            if ($suffixLength < 0)
            {
                $suffixLength = 0;
            }

            $_suffixLength[$table] = $suffixLength;
        }
        else
        {
            $suffixLength = $_suffixLength[$table];
        }

        if (! array_key_exists($table, $_suffixLengthMax))
        {
            $suffixLengthMax = $this->getTableSuffixLengthMax($table);

            if ($suffixLengthMax < $suffixLength)
            {
                $suffixLengthMax = $suffixLength;
            }

            $_suffixLengthMax[$table] = $suffixLengthMax;
        }
        else
        {
            $suffixLengthMax = $_suffixLengthMax[$table];
        }

        if (! array_key_exists($table, $_suffixOffset))
        {
            $suffixOffset = $this->getTableSuffixOffset($table);

            if ($suffixOffset < 0)
            {
                $suffixOffset = 0;
            }

            $_suffixOffset[$table] = $suffixOffset;
        }
        else
        {
            $suffixOffset = $_suffixOffset[$table];
        }

        $key = EhrlichAndreas_Util_Array::objectToArray($key);

        if (is_array($key))
        {
            $key = implode('-', $key);
        }

        $key = strval($key);

        $keyMD5 = md5($key);

        $varPrefix = '';

        if ($prefixLength > 0)
        {
            $varPrefix = $keyMD5;
            
            $varPrefix = substr($varPrefix, $prefixOffset, $prefixLength);
            
            $varPrefix = str_repeat('0', $prefixLengthMax - $prefixLength) . $varPrefix;
        }

        $varSuffix = '';

        if ($suffixLength > 0)
        {
            $varSuffix = $keyMD5;
            
            $varSuffix = substr($varSuffix, $suffixOffset, $suffixLength);
            
            $varSuffix = str_repeat('0', $suffixLengthMax - $suffixLength) . $varSuffix;
        }

        $index = $table;
        
        $index .= '-' . $varPrefix . '-' . $prefix . '-' . $prefixLength . '-' . $prefixLengthMax . '-' . $prefixOffset;
        
        $index .= '-' . $varSuffix . '-' . $suffix . '-' . $suffixLength . '-' . $suffixLengthMax . '-' . $suffixOffset;

        if (isset($names[$index]))
        {
            return $names[$index];
        }

        $varTable = '';

        if (strlen($prefix) > 0)
        {
            if (strlen($varTable) > 0)
            {
                $varTable = $varTable . '_' . $prefix;
            }
            else
            {
                $varTable = $prefix;
            }
        }

        if (strlen($varPrefix) > 0)
        {
            if (strlen($varTable) > 0)
            {
                $varTable = $varTable . '_' . $varPrefix;
            }
            else
            {
                $varTable = $varPrefix;
            }
        }

        if (strlen($varTable) > 0)
        {
            $varTable = $varTable . '_' . $table;
        }
        else
        {
            $varTable = $table;
        }

        if (strlen($suffix) > 0)
        {
            if (strlen($varTable) > 0)
            {
                $varTable = $varTable . '_' . $suffix;
            }
            else
            {
                $varTable = $suffix;
            }
        }

        if (strlen($varSuffix) > 0)
        {
            if (strlen($varTable) > 0)
            {
                $varTable = $varTable . '_' . $varSuffix;
            }
            else
            {
                $varTable = $varSuffix;
            }
        }

        $names[$index] = $varTable;

        return $varTable;
    }

    /**
     * Return the connection resource
     * If we are not connected, the connection is made
     *
     * @throws EhrlichAndreas_AbstractCms_Exception
     * @return EhrlichAndreas_Db_Adapter_Abstract Connection resource
     */
    public function getConnection ()
    {
        if (! empty($this->db) || ! empty($this->options['db']))
        {

            if (EhrlichAndreas_Util_Object::isInstanceOf($this->db, 'EhrlichAndreas_Db_Adapter_Abstract') || EhrlichAndreas_Util_Object::isInstanceOf($this->db, 'Zend_Db_Adapter_Abstract'))
            {
                return $this->db;
            }
            else
            {
                $this->db = $this->options['db'];

                if (! EhrlichAndreas_Util_Object::isInstanceOf($this->db, 'EhrlichAndreas_Db_Adapter_Abstract') && ! EhrlichAndreas_Util_Object::isInstanceOf($this->db, 'Zend_Db_Adapter_Abstract'))
                {
                    $this->throwException("Impossible to open " . $this->options['db'] . " DB", $this->options['exceptionclass']);
                }

                return $this->db;
            }
        }
        elseif (! empty($this->options['dbconfig']))
        {

            $adapter = EhrlichAndreas_Db_Db::getAdapterName($this->options['dbconfig']);

            if (! EhrlichAndreas_Util_Object::isInstanceOf($adapter, 'EhrlichAndreas_Db_Adapter_Abstract') && ! EhrlichAndreas_Util_Object::isInstanceOf($this->db, 'Zend_Db_Adapter_Abstract'))
            {
                $this->throwException("Impossible to open " . $adapter . " DB", $this->options['exceptionclass']);
            }

            $dbconfig = $this->options['dbconfig'];

            if (isset($dbconfig['dbtableprefix']))
            {
                unset($dbconfig['dbtableprefix']);
            }
            
            if (isset($dbconfig['dbtableprefixlength']))
            {
                unset($dbconfig['dbtableprefixlength']);
            }
            
            if (isset($dbconfig['dbtableprefixlengthmax']))
            {
                unset($dbconfig['dbtableprefixlengthmax']);
            }
            
            if (isset($dbconfig['dbtableprefixoffset']))
            {
                unset($dbconfig['dbtableprefixoffset']);
            }
            
            if (isset($dbconfig['dbtablesuffix']))
            {
                unset($dbconfig['dbtablesuffix']);
            }
            
            if (isset($dbconfig['dbtablesuffixlength']))
            {
                unset($dbconfig['dbtablesuffixlength']);
            }
            
            if (isset($dbconfig['dbtablesuffixlengthmax']))
            {
                unset($dbconfig['dbtablesuffixlengthmax']);
            }
            
            if (isset($dbconfig['dbtablesuffixoffset']))
            {
                unset($dbconfig['dbtablesuffixoffset']);
            }

            $this->db = EhrlichAndreas_Db_Db::factory($dbconfig);

            return $this->db;
        }
        else
        {
            $this->throwException("Impossible to open DB", $this->options['exceptionclass']);
        }
    }

    public function closeConnection ()
    {
        $this->getConnection()->closeConnection();
    }

    /**
     *
     * @return array options
     */
    public function getOptions ()
    {
        return $this->options;
    }

    /**
     *
     * @return int
     */
    protected function getVersion ()
    {
        return 0;
    }

    /**
     *
     * @return int
     */
    protected function getVersionMax ()
    {
        $versions = $this->getVersions();

        if (empty($versions))
        {
            return 0;
        }

        $versions = array_keys($versions);

        $version = $versions[count($versions) - 1];

        return $version;
    }

    /**
     *
     * @return array
     */
    protected function getVersions ()
    {
        $class = get_class($this);

        /*
         * $pos = stripos($class, 'Adapter'); if (false !== $pos) { $class =
         * substr($class, 0, $pos); }
         */

        $return = array();

        $methods = get_class_methods($class);

        $pattern = '#^(install\_version\_\d+)$#';

        foreach ($methods as $method)
        {
            if (preg_match($pattern, $method))
            {
                $version = explode('_', $method);
                
                $version = $version[count($version) - 1];

                $return[$version] = $method;
            }
        }

        ksort($return);

        return $return;
    }

    /**
     *
     * @return void
     */
    public function install ()
    {
        $version = $this->getVersion();
        
        $version_max = $this->getVersionMax();

        if ($version >= $version_max)
        {
            return;
        }

        $versions = $this->getVersions();

        foreach ($versions as $version => $method)
        {
            $this->$method();
            
            $this->setVersion($version);
        }
    }

    /**
     *
     * @param int $version
     * @return void
     */
    protected function setVersion ($version = 0)
    {
    }

    /**
     * Throw an exception
     * Note : for perf reasons, the "load" of EhrlichAndreas_AbstractCms_Exception is
     * dynamic
     *
     * @param string $msg
     *            Message for the exception
     * @throws EhrlichAndreas_AbstractCms_Exception
     */
    public static function throwException ($msg, $className = 'EhrlichAndreas_AbstractCms_Exception')
    {
        // For perfs reasons, we use this dynamic inclusion
        throw new $className($msg);
    }
}

