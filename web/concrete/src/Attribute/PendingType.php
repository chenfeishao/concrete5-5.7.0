<?php
namespace Concrete\Core\Attribute;
use \Concrete\Core\Foundation\Object;
use Loader;
class PendingType extends Type {

	public static function getList() {
		$db = Loader::db();
		$atHandles = $db->GetCol("select atHandle from AttributeTypes");

		$dh = Loader::helper('file');
		$available = array();
		if (is_dir(DIR_APPLICATION . '/' . DIRNAME_ATTRIBUTES)) {
			$contents = $dh->getDirectoryContents(DIR_APPLICATION . '/' . DIRNAME_ATTRIBUTES);
			foreach($contents as $atHandle) {
				if (!in_array($atHandle, $atHandles)) {
					$available[] = static::getByHandle($atHandle);
				}
			}
		}
		return $available;
	}

	public static function getByHandle($atHandle) {
		$th = Loader::helper('text');
		if (file_exists(DIR_APPLICATION . '/' . DIRNAME_ATTRIBUTES . '/' .  $atHandle)) {
			$at = new static();
			$at->atID = 0;
			$at->atHandle = $atHandle;
			$at->atName = $th->unhandle($atHandle);
			return $at;
		}
	}

	public function install() {
		parent::add($this->atHandle, $this->atName);
	}

}
