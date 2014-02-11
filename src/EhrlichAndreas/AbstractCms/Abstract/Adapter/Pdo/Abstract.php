<?php 

//require_once 'EhrlichAndreas/AbstractCms/Exception.php';

//require_once 'EhrlichAndreas/AbstractCms/Abstract/Adapter/Abstract.php';

//require_once 'EhrlichAndreas/Db/Expr.php';

//require_once 'EhrlichAndreas/Db/Delete.php';

//require_once 'EhrlichAndreas/Db/Insert.php';

//require_once 'EhrlichAndreas/Db/Select.php';

//require_once 'EhrlichAndreas/Db/Update.php';

/**
 *
 * @author Ehrlich, Andreas <ehrlich.andreas@googlemail.com>
 */
abstract class EhrlichAndreas_AbstractCms_Abstract_Adapter_Pdo_Abstract extends EhrlichAndreas_AbstractCms_Abstract_Adapter_Abstract
{
    /**
     *
     * @var int 
     */
    protected $version = 0;

    protected function getVersion ()
    {
        $dbAdapter = $this->getConnection();
        
        $tableVersion = $this->getTableName('version');
        
        return $this->_getVersion($dbAdapter, $tableVersion);
    }

    protected function setVersion ($version = 0)
    {
        $dbAdapter = $this->getConnection();
        
        $tableVersion = $this->getTableName('version');
        
        return $this->_setVersion($dbAdapter, $tableVersion, $version);
    }
	
	/**
	 * 
	 * @param Zend_Db_Adapter_Abstract $dbAdapter
	 * @param string $tableVersion
	 * @param string $defaultVersion
	 * @return type
	 */
	protected function _getVersion ($dbAdapter, $tableVersion, $defaultVersion = 0)
	{
        if ($this->version > 0)
        {
            return $this->version;
        }
        
        $query = new EhrlichAndreas_Db_Select($dbAdapter);
		
        $query->from($tableVersion, array('version' => new EhrlichAndreas_Db_Expr('max(num)')));
		
        $sql = $query->assemble();

        try
        {
            $result = $dbAdapter->fetchAll($sql);
			
            if (isset($result[0]['version']))
            {
                $versionDb = $result[0]['version'];
            }
            else
            {
                $versionDb = $defaultVersion;
            }
        }
        catch (Exception $e)
        {
            $versionDb = $defaultVersion;
            
            /*
            echo $e;die();
            try
            {
                $result = $dbAdapter->fetchAll($sql);

                if (isset($result[0]['version']))
                {
                    $versionDb = $result[0]['version'];
                }
                else
                {
                    $versionDb = $defaultVersion;
                }
            }
            catch (Exception $e)
            {
                $versionDb = $defaultVersion;
            }
             * 
             */
        }
        
        $this->version = $versionDb;
		
		return $versionDb;
	}
	
	/**
	 * 
	 * @param Zend_Db_Adapter_Abstract $dbAdapter
	 * @param string $tableVersion
	 * @param string $version
	 * @return type
	 */
	protected function _setVersion ($dbAdapter, $tableVersion, $version = 0)
	{
        $query = new EhrlichAndreas_Db_Insert($dbAdapter);
		
		$query->into($tableVersion);
		
		$query->insert('num', $version);
		
        $sql = $query->assemble();

        try
        {
			$stmt = $dbAdapter->query($sql);
			
			$result = $stmt->rowCount();
		}
        catch (Exception $e)
        {
			$result = false;
        }
        
        if ($result)
        {
            $this->version = $version;
        }
		
		return $result;
	}
}

?>