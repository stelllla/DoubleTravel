<?php

class Country
{
	/** @var string  */
	public $name = '';
	/** @var string  */
	public $capital = '';
	/** @var string  */
	public $nativeName = '';
	/** @var string  */
	public $region = '';
	/** @var string  */
	public $subregion = '';
	/** @var int  */
	public $population = 0;
	/** @var int  */
	public $area = 0;
	/** @var string  */
	public $currencies = '';
	/** @var array  */
	public $latlng = [23,45];
	/** @var string  */
	public $languages = '';

	public function __construct($data = NULL)
	{
		if (!is_array($data)) {
			return;
		}

		foreach (get_object_vars($this) as $name => $v) {
			if (!array_key_exists($name, $data)) {
				continue;
			}
			$v = $data[$name];
			if ($name == 'currencies' || $name == 'languages') {
				$v = implode(' ', $v);
			}
			$this->$name = $v;
		}
	}

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