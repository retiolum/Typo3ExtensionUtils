<?php

namespace etobi\extensionUtils\Model;

/**
 * representation of an item from the TER SOAP API search
 */
class TerExtensionKeyInfo implements \ArrayAccess, \IteratorAggregate {

	protected $data = array();

	public function __construct($data = array()) {
		if($data) {
			$this->data = $data;
		}
	}

	public function offsetExists($offset) {
		return array_key_exists($offset, $this->data);
	}

	public function offsetGet($offset) {
		return $this->offsetExists($offset) ? $this->data[$offset] : NULL;
	}

	public function offsetSet($offset, $value) {
		throw new \BadMethodCallException('offsetSet is not implemented');
	}

	public function offsetUnset($offset) {
		throw new \BadMethodCallException('offsetUnset is not implemented');
	}

	public function getExtensionKey() {
		return $this->offsetGet('extensionkey');
	}

	public function getTitle() {
		return $this->offsetGet('title');
	}

	public function getDescription() {
		return $this->offsetGet('description');
	}

	public function getOwnerUsername() {
		return $this->offsetGet('ownerusername');
	}

	public function getIterator()
	{
		return new \ArrayObject($this->data);
	}
}