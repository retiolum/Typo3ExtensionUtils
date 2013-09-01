<?php

namespace etobi\extensionUtils\Command\Ter;

use etobi\extensionUtils\Controller\SelfController;

use etobi\extensionUtils\T3oSoap\Exception\ExtensionKeyNotExistsException;
use etobi\extensionUtils\T3oSoap\Exception\ExtensionKeyNotValidException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * TerTransferExtensionKeyCommand registers a given extension key
 *
 * @author Christian Zenker <christian.zenker@599media.de>
 */
class TransferExtensionKeyCommand extends AbstractAuthenticatedTerCommand
{

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('ter:transfer-key')
            ->setDefinition(array(
                new InputArgument('extensionKey', InputArgument::OPTIONAL, 'the extension key to register'),
                new InputArgument('targetUsername', InputArgument::OPTIONAL, 'the username to transfer the key to'),
            ))
            ->setDescription('Transfer an extension key to a different user')
            ->setHelp(<<<EOT
Transfer an extension key to a different user

Example
=======

Transfer extension key "my_extension" to user "kasper"

  t3xutils ter:transfer-key my_extension kasper

.t3xuconfig
===========

* <info>ter.username</info>: username on typo3.org
* <info>ter.password</info>: password on typo3.org
* <info>ter.wsdl</info>: wsdl url for the Soap API

Return codes
============

* `0` if the key is transfered
* `1` if the key could not be transfered
* `2` if the key is formally invalid

EOT
)
        ;
        $this->configureSoapOptions();
        $this->configureCredentialOptions();
    }

    protected function prepareParameters(InputInterface $input, OutputInterface $output)
    {
        if(!$input->getArgument('extensionKey')) {

            $extensionKey = $this->getDialogHelper()->ask(
                $output,
                '<question>extension key:</question> '
            );
            $this->logger->debug(sprintf('interactively asked for extension key. "%s" given', $extensionKey));
            $input->setArgument('extensionKey', $extensionKey);
        }
	    if(!$input->getArgument('targetUsername')) {

		    $targetUsername = $this->getDialogHelper()->ask(
			    $output,
			    '<question>transfer to username:</question> '
		    );
		    $this->logger->debug(sprintf('interactively asked for targetUsername. "%s" given', $targetUsername));
		    $input->setArgument('targetUsername', $targetUsername);
	    }
	    parent::prepareParameters($input, $output);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $extensionKey = $input->getArgument('extensionKey');
	    /** @var \etobi\extensionUtils\T3oSoap\TransferExtensionKeyRequest $transferRequest */
	    $transferRequest = $this->getRequestObject('\\etobi\\extensionUtils\\T3oSoap\\TransferExtensionKeyRequest');
        try {
            $result = $transferRequest->transferExtensionKey(
                $extensionKey,
                $input->getArgument('targetUsername')
            );

            if($result) {
                $output->writeln(sprintf('"%s" successfully transfered to "%s"', $extensionKey, $input->getArgument('targetUsername')));
                return 0;
            } else {
                $output->writeln(sprintf('<error>"%s" could not be transfered</error>', $extensionKey));
                return 1;
            }
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            return 1;
        }
    }
}
