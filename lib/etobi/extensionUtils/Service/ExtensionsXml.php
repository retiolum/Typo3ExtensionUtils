<?php

namespace etobi\extensionUtils\Service;

/**
 * service to work with the extensions XML from typo3.org
 */
class ExtensionsXml {

	/**
	 * @var string
	 */
	protected $extensionsXmlFile = '/tmp/t3xutils.extensions.temp.xml';

	/**
	 * @param string|null $extensionXmlFile
	 */
	public function __construct($extensionXmlFile = NULL) {
		if (!is_null($extensionXmlFile)) {
			$this->extensionsXmlFile = $extensionXmlFile;
		}
	}

	public function isFileValid() {
		$this->ensureValidFile();
	}

	/**
	 * check that the
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function ensureValidFile() {
		if (empty($this->extensionsXmlFile)) {
			throw new \InvalidArgumentException('No extension file is set');
		}
		if (!file_exists($this->extensionsXmlFile)) {
			throw new \InvalidArgumentException(sprintf('The extension file "%s" does not exist', $this->extensionsXmlFile));
		}
		if (!is_readable($this->extensionsXmlFile)) {
			throw new \InvalidArgumentException(sprintf('The extension file "%s" is not readable', $this->extensionsXmlFile));
		}
	}

	/**
	 * find the latest version of a given extension key
	 *
	 * @param $extensionKey
	 * @return null|string
	 */
	public function findLatestVersion($extensionKey) {
		$result = $this->query(
			'/extensions/extension[@extensionkey="' . $extensionKey . '"]'
		);
		$newestDate = -1;
		$version = NULL;
		foreach ($result->item(0)->childNodes as $versionNode) {
			if ($versionNode->nodeName == 'version' && $versionNode->hasAttribute('version')) {
				$date = $versionNode->getElementsByTagName('lastuploaddate')->item(0)->nodeValue;
				if ($date > $newestDate) {
					$newestDate = $date;
					$version = (string)$versionNode->getAttribute('version');
				}
			}
		}

		return $version;
	}

	/**
	 * queryExtensionsXML
	 *
	 * query extension xml
	 *
	 * @param string $xpath xpath query
	 * @throws \RuntimeException
	 * @return \DOMNodeList
	 */
	public function query($xpath) {
		$this->ensureValidFile();

		$doc = new \DOMDocument();
		$contents = file_get_contents($this->extensionsXmlFile);
		if ($contents === FALSE) {
			throw new \RuntimeException(sprintf('Error while reading "%s"', $this->extensionsXmlFile));
		}
		if ($doc->loadXML($contents) === FALSE) {
			throw new \RuntimeException(sprintf('Could not parse the contents of "%s" as XML', $this->extensionsXmlFile));
		};

		$domXpath = new \DOMXpath($doc);

		return $domXpath->query($xpath);
	}

	/**
	 * get all available information on an extension key
	 *
	 * @param $extensionKey
	 * @return array
	 */
	public function getExtensionInfo($extensionKey) {
		$result = $this->query('/extensions/extension[@extensionkey="' . $extensionKey . '"]');

		$versionInfos = array();
		foreach ($result->item(0)->childNodes as $versionNode) {
			/** @var $versionNode \DOMElement */
			if ($versionNode->nodeName == 'version' && $versionNode->hasAttribute('version')) {
				$versionInfos[] = array(
					'version' => $this->sanitizeString($versionNode->getAttribute('version')),
					'comment' => $this->sanitizeString($versionNode->getElementsByTagName('uploadcomment')->item(0)->nodeValue),
					'timestamp' => $this->sanitizeString($versionNode->getElementsByTagName('lastuploaddate')->item(0)->nodeValue),
				);
			}
		}

		return $versionInfos;
	}

	protected function sanitizeString($string) {
		return str_replace("\r", '', $string);
	}

	/**
	 * get all available information on a version
	 *
	 * @param string $extensionKey
	 * @param string $version
	 * @return array
	 */
	public function getVersionInfo($extensionKey, $version) {
		$result = $this->query(sprintf(
			'/extensions/extension[@extensionkey="%s"]/version[@version="%s"]',
			$extensionKey,
			$version
		));
		$infos = array();
		foreach ($result->item(0)->childNodes as $childNode) {
			/** @var $childNode \DOMElement */
			if ($childNode->nodeType == XML_ELEMENT_NODE) {
				if ($childNode->nodeName == 'dependencies') {
					$infos[$childNode->nodeName] = $this->sanitizeDependencies($childNode->nodeValue);
				}
				else {
					$infos[$childNode->nodeName] = $this->sanitizeString($childNode->nodeValue);
				}
			}
		}

		return $infos;
	}

	protected function sanitizeDependencies($dependencies) {
		if (empty($dependencies)) {
			return array();
		}
		else {
			$dependencies = unserialize($dependencies);
			if ($dependencies === FALSE) {
				throw new \RuntimeException('dependency information could not be unserialized');
			}

			return $dependencies;
		}
	}
}
