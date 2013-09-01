<?php

namespace etobi\extensionUtils\T3oSoap;
use etobi\extensionUtils\T3oSoap\Exception\AccessDeniedException;
use etobi\extensionUtils\T3oSoap\Exception\ExtensionKeyNotExistsException;
use etobi\extensionUtils\T3oSoap\Exception\ExtensionKeyNotValidException;
use etobi\extensionUtils\T3oSoap\Exception\UserNotFoundException;

/**
 * transfer extension key to another user user
 */
class TransferExtensionKeyRequest extends AbstractAuthenticatedRequest {

    /**
     * transfer extension key to another user
     *
     * @param string $extensionKey
     * @param string $targetUsername
     * @throws \RuntimeException
     * @throws Exception\ExtensionKeyNotExistsException
     * @throws \InvalidArgumentException
     * @return bool
     */
    public function transferExtensionKey($extensionKey, $targetUsername)
    {
        $extensionKey = (string)$extensionKey;
        $targetUsername = (string)$targetUsername;
        if(empty($extensionKey)) {
            throw new \InvalidArgumentException('extensionKey must not be empty');
        }
        if(empty($targetUsername)) {
            throw new \InvalidArgumentException('targetUsername must not be empty');
        }

        $modifyExtensionKeyData = new \stdClass();
        $modifyExtensionKeyData->ownerUsername = $targetUsername;
	    $modifyExtensionKeyData->extensionKey = $extensionKey;

        $this->createClient();
        $this->client->addArgument($modifyExtensionKeyData);

	    try {
	        $result = $this->client->call('modifyExtensionKey');
	    } catch(\SoapFault $e) {
		    throw $this->convertSoapError($e);
	    }

        if($result['resultCode'] == self::TX_TER_RESULT_GENERAL_OK){
            return TRUE;
        } elseif($result['resultCode'] == self::TX_TER_ERROR_MODIFYEXTENSIONKEY_KEYDOESNOTEXIST) {
            throw new ExtensionKeyNotExistsException(sprintf(
                '"%s" is not registered as extension key',
                $extensionKey
            ));
        } elseif($result['resultCode'] == self::TX_TER_ERROR_GENERAL_USERNOTFOUND) {
            throw new UserNotFoundException(sprintf(
                '"%s" is no known user',
                $targetUsername
            ));
        } else {
            throw new \RuntimeException(sprintf('Soap API responded with an unknown response. result code "%s"', $result['resultCode']));
        }
    }
}