<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class GymTestimonialsTable extends Table{
	
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
	}
	
}