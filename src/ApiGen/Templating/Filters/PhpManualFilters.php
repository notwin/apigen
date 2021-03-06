<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\Templating\Filters;

use ApiGen\Reflection\ReflectionClass;
use ApiGen\Reflection\ReflectionConstant;
use ApiGen\Reflection\ReflectionElement;
use ApiGen\Reflection\ReflectionExtension;
use ApiGen\Reflection\ReflectionFunction;
use ApiGen\Reflection\ReflectionMethod;
use ApiGen\Reflection\ReflectionProperty;


/**
 * Builds links to a element documentation at php.net
 */
class PhpManualFilters extends Filters
{

	const PHP_MANUAL_URL = 'http://php.net/manual/en';


	/**
	 * @param ReflectionElement|ReflectionExtension|ReflectionMethod $element
	 * @return string
	 */
	public function manualUrl($element)
	{
		if ($element instanceof ReflectionExtension) {
			return $this->createExtensionUrl($element);
		}

		$class = $element instanceof ReflectionClass ? $element : $element->getDeclaringClass();
		if ($this->isReservedClass($class)) {
			return self::PHP_MANUAL_URL . '/reserved.classes.php';

		} elseif ($element instanceof ReflectionClass) {
			return $this->createClassUrl($class);

		} elseif ($element instanceof ReflectionMethod) {
			return $this->createMethodUrl($class, $element);

		} elseif ($element instanceof ReflectionFunction) {
			return $this->createFunctionUrl($element);

		} elseif ($element instanceof ReflectionProperty) {
			return $this->createPropertyUrl($class, $element);

		} elseif ($element instanceof ReflectionConstant) {
			return $this->createConstantUrl($class, $element);
		}

		return '';
	}


	/**
	 * @return string
	 */
	private function createExtensionUrl(ReflectionExtension $reflectionExtension)
	{
		$extensionName = strtolower($reflectionExtension->getName());
		if ($extensionName === 'core') {
			return self::PHP_MANUAL_URL;
		}

		if ($extensionName === 'date') {
			$extensionName = 'datetime';
		}

		return self::PHP_MANUAL_URL . '/book.' . $extensionName . '.php';
	}


	/**
	 * @return string
	 */
	private function createClassUrl(ReflectionClass $classReflection)
	{
		return self::PHP_MANUAL_URL . '/class.' . strtolower($classReflection->getName()) . '.php';
	}


	/**
	 * @return string
	 */
	private function createConstantUrl(ReflectionClass $classReflection, ReflectionConstant $reflectionConstant)
	{
		return $this->createClassUrl($classReflection) . '#' . strtolower($classReflection->getName()) .
			'.constants.' . $this->getElementName($reflectionConstant);
	}


	/**
	 * @return string
	 */
	private function createPropertyUrl(ReflectionClass $classReflection, ReflectionProperty $reflectionProperty)
	{
		return $this->createClassUrl($classReflection) . '#' . strtolower($classReflection->getName()) .
			'.props.' . $this->getElementName($reflectionProperty);
	}


	/**
	 * @return string
	 */
	private function createMethodUrl(ReflectionClass $reflectionClass, ReflectionMethod $reflectionMethod)
	{
		return self::PHP_MANUAL_URL . '/' . strtolower($reflectionClass->getName()) . '.' .
			$this->getElementName($reflectionMethod) . '.php';
	}


	/**
	 * @return string
	 */
	private function createFunctionUrl(ReflectionElement $reflectionElement)
	{
		return self::PHP_MANUAL_URL . '/function.' . strtolower($reflectionElement->getName()) . '.php';
	}


	/**
	 * @return bool
	 */
	private function isReservedClass(ReflectionClass $class)
	{
		$reservedClasses = ['stdClass', 'Closure', 'Directory'];
		return (in_array($class->getName(), $reservedClasses));
	}


	/**
	 * @return string
	 */
	private function getElementName(ReflectionElement $element)
	{
		return strtolower(strtr(ltrim($element->getName(), '_'), '_', '-'));
	}

}
