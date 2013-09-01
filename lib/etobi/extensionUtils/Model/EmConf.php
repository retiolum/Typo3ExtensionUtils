<?php

namespace etobi\extensionUtils\Model;

/**
 * representation of an ext_emconf.php file
 */
class EmConf implements \ArrayAccess, \IteratorAggregate {

	const CATEGORY_BACKEND = 'be';
	const CATEGORY_MODULE = 'module';
	const CATEGORY_FRONTEND = 'fe';
	const CATEGORY_PLUGIN = 'plugin';
	const CATEGORY_OTHER = 'misc';
	const CATEGORY_SERVICE = 'services';
	const CATEGORY_TEMPLATE = 'templates';
	const CATEGORY_DOCUMENTATION = 'doc';
	const CATEGORY_EXAMPLE = 'example';

	const STATE_ALPHA = 'alpha';
	const STATE_BETA = 'beta';
	const STATE_STABLE = 'stable';
	const STATE_EXPERIMENTAL = 'experimental';
	const STATE_TEST = 'test';
	const STATE_OBSOLETE = 'obsolete';
	const STATE_EXCLUDE_FROM_UPDATES = 'excludeFromUpdates';
	const STATE_NOT_AVAILABLE = 'n/a';

	const PRIORITY_TOP = 'top';
	const PRIORITY_BOTTOM = 'bottom';
	const PRIORITY_DEFAULT = '';

	/**
	 * @var array extension array data
	 */
	protected $data = NULL;

	/**
	 * comment in the header of the ext_emconf.php file
	 * @var string
	 */
	protected $comment = '';

	public function __construct($data = array(), $comment = '') {
		$this->setConfigurationArray($data);
		if($comment) {
			$this->setComment($comment);
		}
	}

	public function setConfigurationArray($data) {
		if(!(is_array($data) || ($data instanceof \ArrayAccess))) {
			throw new \InvalidArgumentException(__METHOD__ . ' expects an array.');
		}
		$this->data = $data;
	}

	public function getConfigurationArray() {
		return $this->data;
	}


	public function setComment($comment) {
		$this->comment = $comment;
	}

	public function getComment() {
		return $this->comment;
	}

	public function getTitle() {
		return array_key_exists('title', $this->data) ? (string)$this->data['title'] : NULL;
	}

	public function setTitle($title) {
		$this->data['title'] = (string)$title;
	}

	public function getDescription() {
		return array_key_exists('description', $this->data) ? (string)$this->data['description'] : NULL;
	}

	public function setDescription($description) {
		$this->data['description'] = (string)$description;
	}

	public function getCategory() {
		return array_key_exists('category', $this->data) ? (string)$this->data['category'] : NULL;
	}

	public function setCategory($category = NULL) {
		$this->data['category'] = (string)$category;
	}

	public function isShy() {
		return array_key_exists('shy', $this->data) ? (bool)$this->data['shy'] : NULL;
	}

	public function setShy($shy) {
		$this->data['shy'] = $shy ? 1 : 0;
	}

//    public function getDependencies() {
//
//    }
//
//    public function setDependencies($dependencies) {
//
//    }
//
//    public function addDependency($extensionKey, $versionFrom = NULL, $versionTo = NULL) {
//
//    }
//
//    public function removeDependency($extensionKey) {
//
//    }
//
//    public function getConflicts() {
//
//    }
//
//    public function setConflicts($conflicts) {
//
//    }
//
//    public function addConflict($extensionKey, $versionFrom = NULL, $versionTo = NULL) {
//
//    }
//
//    public function removeConflict($extensionKey) {
//
//    }
//
//    public function getSuggestions() {
//
//    }
//
//    public function setSuggestion($dependencies) {
//
//    }
//
//    public function addSuggestion($extensionKey, $versionFrom = NULL, $versionTo = NULL) {
//
//    }
//
//    public function removeSuggestion($extensionKey) {
//
//    }

	public function getPriority() {
		return array_key_exists('priority', $this->data) ? (string)$this->data['priority'] : NULL;
	}

	public function setPriority($priority = NULL) {
		$this->data['priority'] = (string)$priority;
	}

	public function getState() {
		return array_key_exists('state', $this->data) ? (string)$this->data['state'] : NULL;
	}

	public function setState($state = NULL) {
		$this->data['state'] = (string)$state;
	}

//    public function getUploadFolder() {
//
//    }
//
//    public function setUploadFolder($uploadFolder) {
//
//    }
//
//    public function getCreatedDirectories() {
//
//    }
//
//    public function setCreatedDirectories($directories = array()) {
//
//    }
//
//    public function addCreatedDirectory($directoryName) {
//
//    }
//
//    public function removeCreatedDirectory($directoryName) {
//
//    }

	public function hasClearCacheOnLoad() {
		return array_key_exists('clearcacheonload', $this->data) ? (bool)$this->data['clearcacheonload'] : NULL;
	}

	public function setClearCacheOnLoad($clearCacheOnLoad = FALSE) {
		$this->data['clearcacheonload'] = $clearCacheOnLoad ? 1 : 0;
	}

	public function getAuthor() {
		return array_key_exists('author', $this->data) ? (string)$this->data['author'] : NULL;
	}

	public function setAuthor($author = NULL) {
		$this->data['author'] = (string)$author;
	}

	public function getAuthorEmail() {
		return array_key_exists('author_email', $this->data) ? (string)$this->data['author_email'] : NULL;
	}

	public function setAuthorEmail($email = NULL) {
		$this->data['author_email'] = (string)$email;
	}

	public function getAuthorCompany() {
		return array_key_exists('author_company', $this->data) ? (string)$this->data['author_company'] : NULL;
	}

	public function setAuthorCompany($company) {
		$this->data['author_company'] = (string)$company;
	}

	public function getVersion() {
		return array_key_exists('version', $this->data) ? (string)$this->data['version'] : NULL;
	}

	public function setVersion($version) {
		$this->data['version'] = (string)$version;
	}

	public function offsetExists($offset) {
		return array_key_exists($offset, $this->data);
	}

	public function offsetGet($offset) {
		return $this->offsetExists($offset) ? $this->data[$offset] : NULL;
	}

	public function offsetSet($offset, $value) {
		$this->data[$offset] = $value;
	}

	public function offsetUnset($offset) {
		unset($this->data[$offset]);
	}

	public function getIterator() {
		return new \ArrayObject($this->data);
	}
}