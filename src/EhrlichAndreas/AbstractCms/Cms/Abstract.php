<?php 

//require_once 'EhrlichAndreas/AbstractCms/Exception.php';

//require_once 'EhrlichAndreas/AbstractCms/Abstract/Adapter/Abstract.php';

//require_once 'EhrlichAndreas/AbstractCms/Abstract/Model.php';

//require_once 'EhrlichAndreas/Db/Adapter/Abstract.php';

//require_once 'EhrlichAndreas/Util/Object.php';

//require_once 'EhrlichAndreas/Util/Dsn.php';

/**
 *
 * @author Ehrlich, Andreas <ehrlich.andreas@googlemail.com>
 */
class EhrlichAndreas_AbstractCms_Cms_Abstract
{

    /**
     * DB ressource
     *
     * @var EhrlichAndreas_AbstractCms_Abstract_Adapter_Abstract $adapter
     */
    protected $adapter = null;

    /**
     *
     * @var string
     */
    protected $switch = 'mysql';

    /**
     * Available options
     * =====> (string) db :
     * =====> (string) dbtableprefix :
     * =====> (string) dbtablesuffix :
     * =====> (string) dbtableprefixlength :
     * =====> (string) dbtableprefixlengthmax :
     * =====> (string) dbtableprefixoffset :
     * =====> (string) dbtablesuffixlength :
     * =====> (string) dbtablesuffixlengthmax :
     * =====> (string) dbtablesuffixoffset :
     *
     * @var array $options Available options
     */
    protected $options = array();

    /**
     *
     * @var EhrlichAndreas_AbstractCms_Abstract_Model
     */
    protected $modelBasic = null;

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
        elseif (/*empty($options['db']) && */isset($options['dbconfig']))
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
            // DB",$options['exceptionClass']);
        }

        if (empty($options['db']))
        {
            $options['db'] = null;
        }

        if (! isset($options['dbtableprefix']) && isset($options['dbconfig']['dbtableprefix']))
        {
            $options['dbtableprefix'] = $options['dbconfig']['dbtableprefix'];
        }
        
        if (! isset($options['dbtableprefix']) && isset($options['dbconfig']['params']['dbtableprefix']))
        {
            $options['dbtableprefix'] = $options['dbconfig']['params']['dbtableprefix'];
        }

        if (! isset($options['dbtableprefix']) && isset($options['dbconfig']['dbtableprefix']))
        {
            $options['dbtableprefix'] = $options['dbconfig']['dbtableprefix'];
        }
        
        if (! isset($options['dbtableprefix']) && isset($options['dbconfig']['params']['dbtableprefix']))
        {
            $options['dbtableprefix'] = $options['dbconfig']['params']['dbtableprefix'];
        }

        if (! isset($options['dbtableprefixlength']) && isset($options['dbconfig']['dbtableprefixlength']))
        {
            $options['dbtableprefixlength'] = $options['dbconfig']['dbtableprefixlength'];
        }
        
        if (! isset($options['dbtableprefixlength']) && isset($options['dbconfig']['params']['dbtableprefixlength']))
        {
            $options['dbtableprefixlength'] = $options['dbconfig']['params']['dbtableprefixlength'];
        }

        if (! isset($options['dbtableprefixlengthmax']) && isset($options['dbconfig']['dbtableprefixlengthmax']))
        {
            $options['dbtableprefixlengthmax'] = $options['dbconfig']['dbtableprefixlengthmax'];
        }
        
        if (! isset($options['dbtableprefixlengthmax']) && isset($options['dbconfig']['params']['dbtableprefixlengthmax']))
        {
            $options['dbtableprefixlengthmax'] = $options['dbconfig']['params']['dbtableprefixlengthmax'];
        }

        if (! isset($options['dbtableprefixoffset']) && isset($options['dbconfig']['dbtableprefixoffset']))
        {
            $options['dbtableprefixoffset'] = $options['dbconfig']['dbtableprefixoffset'];
        }
        
        if (! isset($options['dbtableprefixoffset']) && isset($options['dbconfig']['params']['dbtableprefixoffset']))
        {
            $options['dbtableprefixoffset'] = $options['dbconfig']['params']['dbtableprefixoffset'];
        }

        if (! isset($options['dbtablesuffix']) && isset($options['dbconfig']['dbtablesuffix']))
        {
            $options['dbtablesuffix'] = $options['dbconfig']['dbtablesuffix'];
        }
        
        if (! isset($options['dbtablesuffix']) && isset($options['dbconfig']['params']['dbtablesuffix']))
        {
            $options['dbtablesuffix'] = $options['dbconfig']['params']['dbtablesuffix'];
        }

        if (! isset($options['dbtablesuffixlength']) && isset($options['dbconfig']['dbtablesuffixlength']))
        {
            $options['dbtablesuffixlength'] = $options['dbconfig']['dbtablesuffixlength'];
        }
        
        if (! isset($options['dbtablesuffixlength']) && isset($options['dbconfig']['params']['dbtablesuffixlength']))
        {
            $options['dbtablesuffixlength'] = $options['dbconfig']['params']['dbtablesuffixlength'];
        }

        if (! isset($options['dbtablesuffixlengthmax']) && isset($options['dbconfig']['dbtablesuffixlengthmax']))
        {
            $options['dbtablesuffixlengthmax'] = $options['dbconfig']['dbtablesuffixlengthmax'];
        }
        
        if (! isset($options['dbtablesuffixlengthmax']) && isset($options['dbconfig']['params']['dbtablesuffixlengthmax']))
        {
            $options['dbtablesuffixlengthmax'] = $options['dbconfig']['params']['dbtablesuffixlengthmax'];
        }

        if (! isset($options['dbtablesuffixoffset']) && isset($options['dbconfig']['dbtablesuffixoffset']))
        {
            $options['dbtablesuffixoffset'] = $options['dbconfig']['dbtablesuffixoffset'];
        }
        
        if (! isset($options['dbtablesuffixoffset']) && isset($options['dbconfig']['params']['dbtablesuffixoffset']))
        {
            $options['dbtablesuffixoffset'] = $options['dbconfig']['params']['dbtablesuffixoffset'];
        }

        $this->options = $options;
        
        //if (empty($options['db']))
        //{
            $class = '';
            if (! empty($adapter))
            {
                $class = $adapter;
            }
            elseif (! empty($options['db']))
            {
                $class = get_class($options['db']);
            }
            else
            {
                $class = EhrlichAndreas_Db_Db::getAdapterName($options['dbconfig']);
            }

            // TODO
            if (stripos($class, 'ibm') !== false)
            {
                $class = 'Pdo_Ibm';
                $this->switch = 'ibm';
            }
            elseif (stripos($class, 'mssql') !== false)
            {
                $class = 'Pdo_Mssql';
                $this->switch = 'mssql';
            }
            elseif (stripos($class, 'mysql') !== false)
            {
                $class = 'Pdo_Mysql';
                $this->switch = 'mysql';
            }
            elseif (stripos($class, 'oci') !== false)
            {
                $class = 'Pdo_Oci';
                $this->switch = 'oci';
            }
            elseif (stripos($class, 'sqlite') !== false)
            {
                $class = 'Pdo_Pgsql';
                $this->switch = 'pgsql';
            }
            elseif (stripos($class, 'sqlite') !== false)
            {
                $class = 'Pdo_Sqlite';
                $this->switch = 'sqlite';
            }

            $adapterNamespace = 'EhrlichAndreas_AbstractCms_Abstract_Adapter';

            if (isset($options['adapterNamespace']))
            {
                if ($options['adapterNamespace'] != '')
                {
                    $adapterNamespace = $options['adapterNamespace'];
                }

                unset($options['adapterNamespace']);
            }

            $adapterNamespace = trim($adapterNamespace, '_');

            $class = $adapterNamespace . '_' . $class;

            $this->adapter = new $class($options);
        //}
        //else 
        //{
        //    $this->adapter = $options['db'];
        //}

        $this->modelBasic = new EhrlichAndreas_AbstractCms_Abstract_Model();
    }
    
    /**
     * 
     * @param mixed $options
     * @return array
     */
    protected function _getCmsConfigFromAdapter($options = array())
    {
        if (!is_array($options))
        {
            if (EhrlichAndreas_Util_Object::isInstanceOf($options, 'Zend\Db\Adapter\Adapter'))
            {
                $options = new EhrlichAndreas_Db_ZF2Bridge_Adapter($options);
            }
            
            if (EhrlichAndreas_Util_Object::isInstanceOf($options, 'Doctrine\Bundle\DoctrineBundle\Registry'))
            {
                $options = new EhrlichAndreas_Db_DoctrineBridge_Adapter($options);
            }
            
            if (EhrlichAndreas_Util_Object::isInstanceOf($options, 'EhrlichAndreas_Db_Adapter_Abstract') || EhrlichAndreas_Util_Object::isInstanceOf($options, 'Zend_Db_Adapter_Abstract'))
            {
                $adapter = $options;
                
                $dbConfig = $options->getConfig();
                
                if (isset($dbConfig['dsn']))
                {
                    $data = EhrlichAndreas_Util_Dsn::parseDsn($dbConfig['dsn']);
                    
                    foreach ($data as $key => $value)
                    {
                        $dbConfig[$key] = $value;
                    }
                    
                    $data = EhrlichAndreas_Util_Dsn::parseUri($dbConfig['dsn']);
                    
                    if (isset($data[0]))
                    {
                        $dbConfig['adapter'] = ucfirst($data[0]);
                    }
                    
                    if (isset($dbConfig['adapter']) && $dbConfig['driver'])
                    {
                        $dbConfig['adapter'] = $dbConfig['driver'] . '_' . $dbConfig['adapter'];
                    }
                }
                
                $options = array
                (
                    'db'        => $adapter,
                    'dbconfig'  => $dbConfig,
                    'params'    => $dbConfig,
                );
                
                if (isset($dbConfig['adapter']))
                {
                    $options['adapter'] = $dbConfig['adapter'];
                }
                
                if (isset($dbConfig['install']))
                {
                    $options['install'] = $dbConfig['install'];
                }
            }
        }
        
        return $options;
    }

    /**
     * 
     * @return EhrlichAndreas_AbstractCms_Cms_Abstract
     */
    public function install ()
    {
        $this->adapter->install();
        
        return $this;
    }

    /**
     * Return the connection resource
     * If we are not connected, the connection is made
     *
     * @throws EhrlichAndreas_AbstractCms_Exception
     * @return EhrlichAndreas_AbstractCms_Abstract_Adapter_Abstract Connection resource
     */
    public function getAdapter ()
    {
        return $this->adapter;
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
        return $this->adapter->getConnection();
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

