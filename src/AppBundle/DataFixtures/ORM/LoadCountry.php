<?php
// src/AppBundle/DataFixtures/ORM/LoadCountry.php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Country;

class LoadCountry implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$names = array('France', 'Espagne', 'Italie', 'Suisse', 'Angleterre', 'Allemagne');

		foreach($names as $name)
		{
			$country = new Country();
			$country->setName($name);
			$manager->persist($country);
		}

		$manager->flush();
	}
}