<?php
/*
 * Copyright notice
 * (c) 1998-2016 Limbas GmbH - Axel westhagen (support@limbas.org)
 * All rights reserved
 * This script is part of the LIMBAS project. The LIMBAS project is free software; you can redistribute it and/or modify it on 2 Ways:
 * Under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * Or
 * In a Propritary Software Licence http://limbas.org
 * The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the textfile GPL.txt and important notices to the license from the author is found in LICENSE.txt distributed with these scripts.
 * This script is distributed WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 * Version 3.0
 */

/*
 * ID:
 */

class CLimbasSoapFactory {
	/**
	 * @var CLimbasSoapFactory static .
	 */
	private static $_instance = null;
	
	private $objects = array();
	
	public function createTable($name) {
		$name = ucfirst(lmb_strtolower($name));
		if (!isset($this->objects[$name])) {
			$this->objects[$name] = new $name();
		}
		return clone $this->objects[$name];
	}

	public function createArray($name) {
		$name = ucfirst(lmb_strtolower($name));
		$name .= 'Array';
		if (!isset($this->objects[$name])) {
			$this->objects[$name] = new $name();
		}
		return clone $this->objects[$name];
	}

	public function createQuery($name) {
		$name = ucfirst(lmb_strtolower($name));
		$name .= 'Query';
		if (!isset($this->objects[$name])) {
			$this->objects[$name] = new $name();
		}
		return clone $this->objects[$name];
	}

	public function createJoin($name) {
		$name = ucfirst(lmb_strtolower($name));
		$name .= 'Join';
		if (!isset($this->objects[$name])) {
			$this->objects[$name] = new $name();
		}
		return clone $this->objects[$name];
	}

	/**
	 * Static method which returns the singleton instance of this class.
	 *
	 * @return CLdapServer
	 */
	public static function getInstance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new CLimbasSoapFactory();
		}
		return self::$_instance;
	}
	
	protected function __construct() {
		spl_autoload_register(array($this, 'autoload'));
	}
	
	public function autoload($className) {
		if (!file_exists($umgvar['url'].'TEMP/wsdl/models/' . $className . '.class')) { 
			$this->generateClassFile($className); 
		}
		require_once $umgvar['url'].'TEMP/wsdl/models/' . $className . '.class';
		return true;
	}

	private function generateClassFile($className) {
		#error_log('Factory: ' . $className);
		$date = date(DATE_RSS);
		$filename = $umgvar['url'].'TEMP/wsdl/models/' . $className . '.class';
		$fh = fopen($filename, 'w+');
		if (false !== $fh) {
			if (false !== lmb_strpos($className, 'Array')) {
				fwrite($fh, <<<EOS
<?php
/*
 * Generated by CLimbasSoapFactory
* Tue, 04 Jun 2013 14:32:02 +0200
*/
class $className {
	public \$items = array();
}
EOS
);
				fclose($fh);
			} else {
				if (false !== lmb_strpos($className, 'Query')) {
					$tableName = lmb_strtoupper(lmb_substr($className, 0, lmb_strlen($className) - 5));
					$baseClass = 'CLimbasSoapQuery';
				}		
				elseif (false !== lmb_strpos($className, 'Join')) {
					$tableName = lmb_strtoupper(lmb_substr($className, 0, lmb_strlen($className) - 4));
					$baseClass = 'CLimbasSoapJoin';		
				}
				else {
					$tableName = lmb_strtoupper($className);
					$baseClass = 'CLimbasSoapTable';
				}
			
				fwrite($fh, <<<EOS
<?php
/*
 * Generated by CLimbasSoapFactory
 * $date
 */
class $className extends $baseClass {
	public function __construct()
	{
		parent::__construct('$tableName');
	}
	public function __set(\$name, \$value)
	{
		if (\$this->attributes === array()) {
			\$this->table = '$tableName';
			\$this->createAttributes();
		}
		parent::__set(\$name, \$value);
	}
}
EOS
);
				fclose($fh);				
			}
		}
		else {
			throw new RuntimeException('Unable to open class file "' . $filename . '"!');
		}
	}
	/**
	 * Is there an instance of this class?
	 *
	 * @return boolean whether the instance was created or not
	 */
	public static function hasInstance() {
		return !is_null(self::$_instance);
	}
	
	/**
	 * Don't allow cloning of this class from outside
	 */
	private function __clone() {}
	}