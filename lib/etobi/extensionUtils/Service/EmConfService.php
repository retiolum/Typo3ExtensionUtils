<?php

namespace etobi\extensionUtils\Service;
use etobi\extensionUtils\Model\EmConf;

/**
 * reader and writer for ext_emconf.php
 */
class EmConfService {

	/**
	 * read a php file and return an EmConf model
	 * 
	 * @param $fileName
	 * @return EmConf
	 * @throws \InvalidArgumentException
	 */
	public function readFile($fileName) {
		if(!file_exists($fileName)) {
			throw new \InvalidArgumentException(sprintf('File "%s" does not exist', $fileName));
		}
		if(!is_file($fileName)) {
			throw new \InvalidArgumentException(sprintf('"%s" is not a file', $fileName));
		}
		if(!is_readable($fileName)) {
			throw new \InvalidArgumentException(sprintf('File "%s" is not readable', $fileName));
		}

		$data = $this->readConfigurationArrayFromFile($fileName);
		$comment = $this->readCommentFromFile($fileName);

		return new EmConf($data,$comment);
	}

	/**
	 * read a string containing a php file and return an EmConf model
	 * 
	 * @param $string
	 * @return EmConf
	 * @throws \RuntimeException
	 */
	public function readString($string) {
		$fileName = tempnam(sys_get_temp_dir(), 'test');
		$success = file_put_contents($fileName, $string);
		if(!$success) {
			throw new \RuntimeException(sprintf('Could not write to file "%s".', $fileName));
		}
		$return = $this->readFile($fileName);
		unlink($fileName);
		return $return;
	}

	/**
	 * write an EmConf model to a file
	 *
	 * @param EmConf $emConf
	 * @param string $fileName
	 * @return bool
	 * @throws \RuntimeException
	 */
	public function writeFile($emConf, $fileName) {
		$success = file_put_contents($fileName, $this->writeString($emConf));

		if(!$success) {
			throw new \RuntimeException(sprintf('Could not write to file "%s".', $fileName));
		}

		return TRUE;
	}

	/**
	 * return a php string representation of an EmConf
	 *
	 * @param EmConf $emConf
	 * @return string
	 */
	public function writeString($emConf) {
		$string = "<?php\n\n";
		$string .= $emConf->getComment();
		$string .= "\n\$EM_CONF[\$_EXTKEY] = ";
		$string .= var_export($emConf->getConfigurationArray(), TRUE);
		$string .= ";\n\n?>";
		$string = preg_replace('/^(  )/ms', "\t", $string);
		return $string;
	}


	protected function readConfigurationArrayFromFile($fileName) {
		$EM_CONF = array();
		$_EXTKEY = 'foobar';
		require $fileName;
		return $EM_CONF[$_EXTKEY];
	}

	protected function readCommentFromFile($fileName) {
		$fh = fopen($fileName, 'r');
		// variable to mimic a flip flop (from PERL)
		$started = FALSE;
		$comment = '';
		while($line = fgets($fh)) {
			// match any line with a block comment
			if(preg_match('|^\s*/?\*|',  $line) > 0) {
				$started = TRUE;
				$comment .= $line;
			} elseif($started === TRUE) {
				return $comment;
			}
		}
		return $comment;
	}
}