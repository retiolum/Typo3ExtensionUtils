<?php

namespace etobi\extensionUtils\T3oSoap;

/**
 * search for extension keys
 */
class UploadRequest extends AbstractAuthenticatedRequest {

	/**
	 * @var null|array
	 */
	protected $lastResultMessages = NULL;
	/**
	 * @var null|string
	 */
	protected $lastVersion = NULL;

	/**
	 * @param array $extensionData
	 * @param array $filesData
	 * @return boolean
	 * @throws \RuntimeException
	 * @throws \Exception
	 */
	public function upload(array $extensionData, array $filesData) {
		$this->lastResultMessages = NULL;
		$this->lastVersion = NULL;

		$this->createClient();
		$this->client->addArgument($extensionData);
		$this->client->addArgument($filesData);

		try {
			$response = $this->client->call('uploadExtension');
		} catch(\SoapFault $e) {
			throw $this->convertSoapError($e);
		}

		if(!is_array($response) || !array_key_exists('resultCode', $response)) {
			throw new \RuntimeException('Soap API responded in an unknown format: ' . gettype($response));
		}
		if($response['resultCode'] !== self::TX_TER_RESULT_EXTENSIONSUCCESSFULLYUPLOADED) {
			throw new \RuntimeException(sprintf('Soap API responded with an unknown response. result code "%s"', $response['resultCode']));
		}

		$this->lastResultMessages = $response['resultMessages'];
		$this->lastVersion = $response['version'];

		return TRUE;
	}

	/**
	 * @return array|null
	 */
	public function getLastResultMessages()
	{
		return $this->lastResultMessages;
	}

	/**
	 * @return null|string
	 */
	public function getLastVersion()
	{
		return $this->lastVersion;
	}


}