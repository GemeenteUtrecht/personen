<?php
// api/src/Controller/CreateBookPublication.php

namespace App\Controller;

use App\Entity\Persoon;

class BRPController
{
	public function __invoke(Persoon $data): Persoon
	{
		//$this->bookPublishingHandler->handle($data);
		
		return $data;
	}
}