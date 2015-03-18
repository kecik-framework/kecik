<?php
namespace Model;

use Kecik\Model;

class Data extends Model {

	protected $table = 'data';

	public function __construct($id='') {
		parent::__construct($id);
	}
}