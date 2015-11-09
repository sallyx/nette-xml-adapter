<?php

/**
 * Copyright (c) 2015 Petr Bilek (http://ww.sallyx.org)
 */

namespace Sallyx\Nette;

/**
 * Use this trait to extend \Nette\Configurator
 * Add ability to add adapters
 *
 */
trait AdapterManager
{

	/**
	 * @var array  string => IAdapter|string
	 */
	private $adapters = array();

	/**
	 * @param string $extension
	 * @param string|Nette\DI\Config\IAdapter $adapter
	 * @return self
	 */
	public function addAdapter($extension, $adapter)
	{
		$this->adapters[strtolower($extension)] = $adapter;
		return $this;
	}

	/**
	 * @return DI\Config\Loader
	 */
	protected function createLoader()
	{
		$loader = parent::createLoader();
		foreach ($this->adapters as $extension => $adapter) {
			$loader->addAdapter($extension, $adapter);
		}
		return $loader;
	}

}
