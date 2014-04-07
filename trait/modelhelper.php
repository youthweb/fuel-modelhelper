<?php
/**
 * Trait with helper methods for fuel models
 *
 * Part of youthweb.net
 *
 * @author Youthweb Development Team
 * @license MIT License
 * @copyright 2014 Youthweb Development Team
 * @link http://youthweb.net
 */

/**
 * Trait with helper methods for fuel models
 *
 * @package     App
 * @author      Artur Weigandt <art4@wlabs.de>
 * @since       2014-04-04
 */

trait Trait_ModelHelper
{

	/**
	 * @var  array  $_rename_properties  The named properties (must set this in your Model)
	 */
	// protected static $_rename_properties = array();

	/**
	 * Magic Caller for getter und setter
	 */
	public function __call($method, $args)
	{
		// \Orm\Model has a __call method
		if ( method_exists($this, '__call') )
		{
			try
			{
				return parent::__call($method, $args);
			}
			catch ( \BadMethodCallException $e ) { }
		}

		// getter method
		if ( substr( $method, 0, 3 ) == 'get' )
		{
			// $method = 'get...'
			if ( strlen($method) > 3 )
			{
				$key = substr( $method, 3 );

				if ( count( $args ) > 0 )
				{
					$default = $args[0];
					$default_given = true;
				}
				else
				{
					$default = null;
					$default_given = false;
				}
			}
			// $method = 'get'
			// \Fuel\Core\Model_Crud has no get() method
			else
			{
				if ( count( $args ) == 0 )
				{
					throw new \InvalidArgumentException('Missing argument 1 in call to '.get_class($this).'::'.$method.'().');
				}

				$key = $args[0];

				if ( count( $args ) > 1 )
				{
					$default = $args[1];
					$default_given = true;
				}
				else
				{
					$default = null;
					$default_given = false;
				}
			}

			$prop = $key;

			// Aliasing for properties
			if ( isset(static::$_rename_properties) and isset(static::$_rename_properties[$key]) )
			{
				$prop = static::$_rename_properties[$key];
			}

			// get property from model
			if ( isset($this->$prop) )
			{
				return $this->$prop;
			}

			// return $default if given
			if ( $default_given )
			{
				return $default;
			}
		}

		// setter
		if ( substr( $method, 0, 3 ) == 'set' )
		{
			$key = substr( $method, 3 );

			if ( count( $args ) == 0 )
			{
					throw new \InvalidArgumentException('You need to pass a value to '.$method.'().');
			}

			$value = $args[0];

			$prop = $key;

			// Aliasing for properties
			if ( isset(static::$_rename_properties) and isset(static::$_rename_properties[$key]) )
			{
				$prop = static::$_rename_properties[$key];
			}

			return $this->set(array($prop => $value));
		}

		throw new \BadMethodCallException('Invalid method: '.get_class($this).'::'.$method.'()');
	}

}

