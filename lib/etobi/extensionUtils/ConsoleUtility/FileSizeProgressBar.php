<?php

namespace etobi\extensionUtils\ConsoleUtility;


use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * show the progress of a file download on OutputInterface
 */
class FileSizeProgressBar {

	/**
	 * @var ProgressHelper
	 */
	protected $progressHelper;

	/**
	 * @var OutputInterface
	 */
	protected $output;

	/**
	 * if start() method on $this->progressHelper was called
	 *
	 * @var bool
	 */
	protected $isStarted = FALSE;

	/**
	 * if download seems to be finished
	 *
	 * @var bool
	 */
	protected $isFinished = FALSE;

	/**
	 * @param ProgressHelper $progressHelper
	 * @param OutputInterface $output
	 */
	public function __construct(ProgressHelper $progressHelper, OutputInterface $output) {
		$this->progressHelper = $progressHelper;
		$this->output = $output;
	}

	/**
	 * the callback method that should be called by CURL
	 *
	 * @param resource $curl
	 * @param int $downloadExpected
	 * @param int $downloaded
	 * @param int $uploadExpected
	 * @param int $uploaded
	 */
	public function progressCallback($curl, $downloadExpected = 0, $downloaded = 0, $uploadExpected = 0, $uploaded = 0) {
		// NOTE: for some reason the first call by CURL is with both parameters set to 0
		if ($downloadExpected == 0) {
			return;
		}

		if ($this->isFinished) {
			return;
		}

		if (!$this->isStarted) {
			$this->start($downloadExpected);
		}

		$this->progressHelper->setCurrent($downloaded, TRUE);

		if ($downloadExpected == $downloaded) {
			$this->output->writeln(''); // write newline
			// NOTE: the callback is called multiple times with the same totalSize and downloadedSize by CURL
			$this->isFinished = TRUE;
		}
	}

	/**
	 * @param integer $totalSize
	 */
	protected function start($totalSize) {
		$this->progressHelper->start($this->output, $totalSize);

		$this->isStarted = TRUE;
	}

}
