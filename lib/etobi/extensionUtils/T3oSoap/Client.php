<?php

namespace etobi\extensionUtils\T3oSoap;

/**
 * a wrapper for the SOAP Client to have a more flexible API and shorter code
 */
class Client {

	/**
	 * @var string
	 */
	protected $wsdlURL;

	/**
	 * @var array
	 */
	protected $soapOptions = array(
		'trace' => TRUE,
		'exceptions' => TRUE,
	);

	/**
	 * @var string the function name to call
	 */
	protected $functionName;

	/**
	 * @var array the arguments to pass to the call
	 */
	protected $arguments = array();

	/**
	 * @param string $wsdlURL
	 */
	public function __construct($wsdlURL) {
		$this->wsdlURL = $wsdlURL;
	}

	/**
	 * execute the call
	 *
	 * @param string|null $functionName the function name to call
	 * @param array|null $arguments will be added to the already set arguments
	 * @return mixed
	 */
	public function call($functionName = NULL, $arguments = array()) {
		if ($functionName) {
			$this->setFunctionName($functionName);
		}
		if (!empty($arguments)) {
			if (is_array($arguments)) {
				$this->addArguments($arguments);
			}
			else {
				$this->addArgument($arguments);
			}
		}
		$soapClient = $this->getSoapClient();
		$return = $soapClient->__call($this->functionName, $this->arguments);

		return $return;
	}

	/**
	 * @param string $functionName
	 */
	public function setFunctionName($functionName) {
		$this->functionName = $functionName;
	}

	/**
	 * @param array $arguments
	 */
	public function addArguments($arguments) {
		foreach ($arguments as $argument) {
			$this->addArgument($argument);
		}
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function addArgument($value) {
		$this->arguments[] = $value;
	}

	/**
	 * create a new \SoapClient
	 *
	 * @return \SoapClient
	 */
	protected function getSoapClient() {
		return new \SoapClient($this->wsdlURL, $this->soapOptions);
	}
}
