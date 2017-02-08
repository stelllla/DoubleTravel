<?php


class User
{

	/** @var integer */
	public $ID = NULL;
	/** @var string */
	public $PASS = '';
	/** @var string */
	public $EMAIL = '';
	/** @var string */
	public $FIRST_NAME = '';
	/** @var string */
	public $LAST_NAME = '';

	/**
	 * @param null $data Database record to construct object with
	 */
	public function __construct($data = NULL)
	{
		if (!is_array($data)) {
			return;
		}

		foreach (get_object_vars($this) as $name => $v) {
			if (isset($data[$name])) {
				$this->$name = $data[$name];
			}
		}
	}

	/**
	 * Converts the object to an array and prefix every field with $prefix.
	 *
	 * @param string $prefix
	 * @return array
	 */
	public function toArray($prefix = '')
	{
		$ret = array();
		foreach (get_object_vars($this) as $name => $v) {
			if (method_exists($v, 'toArray')) {
				$v = $v->toArray($prefix);
			}
			$ret[$prefix . $name] = $v;
		}
		return $ret;
	}
}
