<?php

namespace ApiGen\Tests;

use Nette\DI\Container;
use PHPUnit_Framework_TestCase;


class ContainerAwareTestCase extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Container
	 */
	protected $container;

	/**
	 * @var string
	 */
	protected $sourceDir;

	/**
	 * @var string
	 */
	protected $destinationDir;


	public function __construct()
	{
		$this->container = (new ContainerFactory)->create();
		$this->sourceDir = $this->container->getParameters()['appDir'] . '/Project';
		$this->destinationDir = $this->container->getParameters()['tempDir'] . '/api';
	}


	/**
	 * @param string $file
	 * @return string
	 */
	protected function getFileContentInOneLine($file)
	{
		$content = file_get_contents($file);
		$content = preg_replace('/\s+/', ' ', $content);
		$content = preg_replace('/(?<=>)\s+|\s+(?=<)/', '', $content);
		return $content;
	}

}
