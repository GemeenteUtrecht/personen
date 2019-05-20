<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


use App\Entity\Persoon;
use App\Entity\Persoon\Naam;
use App\Entity\Persoon\Adres;
use App\Entity\Persoon\Geboorte;
use App\Entity\Persoon\Overlijden;

class PersonFixtureLoader extends Fixture
{
	public function load(ObjectManager $manager)
	{
		$data = json_decode(file_get_contents(__DIR__.'/data.json'),true);
		$persons = $data['results'];
		
		foreach ($persons as $persoon){			
			$persoonEntity= new Persoon();
			$persoonEntity->setBronOrganisatie('123456789');
			$persoonEntity->setBurgerservicenummer($persoon['burgerservicenummer']);
			$persoonEntity->setEmailadres($persoon['emailadres']);
			$persoonEntity->setTelefoonnummer($persoon['telefoonnummer']);
			//$persoonEntity->setFaxNummer($persoon['fax-nummer']);
			//$persoonEntity->setWebsiteUrl($persoon['website-url']);
			//$persoonEntity->setAandudingNaamsgebruik($persoon['aanduding_naamsgebruik']);
			
			$persoonEntity->setAanschrijving(new Naam());
			$persoonEntity->getAanschrijving()->setVoornamen($persoon['aanschrijving']['voornamen']);
			$persoonEntity->getAanschrijving()->setVoorletters($persoon['aanschrijving']['voorletters']);
			$persoonEntity->getAanschrijving()->setAdelijkeTitel($persoon['aanschrijving']['adelijke_titel']);
			$persoonEntity->getAanschrijving()->setVoorvoegselGeslachtsnaam($persoon['aanschrijving']['voorvoegsel_geslachtsnaam']);
			$persoonEntity->getAanschrijving()->setGeslachtsnaam($persoon['aanschrijving']['geslachtsnaam']);
			
			$persoonEntity->setNaam(new Naam());
			$persoonEntity->getNaam()->setVoornamen($persoon['aanschrijving']['voornamen']);
			$persoonEntity->getNaam()->setVoorletters($persoon['aanschrijving']['voorletters']);
			$persoonEntity->getNaam()->setAdelijkeTitel($persoon['aanschrijving']['adelijke_titel']);
			$persoonEntity->getNaam()->setVoorvoegselGeslachtsnaam($persoon['aanschrijving']['voorvoegsel_geslachtsnaam']);
			$persoonEntity->getNaam()->setGeslachtsnaam($persoon['aanschrijving']['geslachtsnaam']);		
			
			$persoonEntity->setVerblijfadres(new Adres());
			$persoonEntity->getVerblijfadres()->setStraatnaam($persoon['verblijfadres']['straatnaam']);
			$persoonEntity->getVerblijfadres()->setHuisnummer($persoon['verblijfadres']['huisnummer']);
			$persoonEntity->getVerblijfadres()->setPostcode($persoon['verblijfadres']['postcode']);
			$persoonEntity->getVerblijfadres()->setWoonplaats($persoon['verblijfadres']['woonplaats']);
			
			$persoonEntity->setPostadres(new Adres());
			$persoonEntity->getPostadres()->setStraatnaam($persoon['postadres']['straatnaam']);
			$persoonEntity->getPostadres()->setHuisnummer($persoon['postadres']['huisnummer']);
			$persoonEntity->getPostadres()->setPostcode($persoon['postadres']['postcode']);
			$persoonEntity->getPostadres()->setWoonplaats($persoon['postadres']['woonplaats']);
			
			$date = \DateTime::createFromFormat('d/m/Y', $persoon['geboorte']['datum']);
			$persoonEntity->setGeboorte(new Geboorte());
			$persoonEntity->getGeboorte()->setDatum($date);
			$persoonEntity->getGeboorte()->setLand($persoon['geboorte']['land']);
			$persoonEntity->getGeboorte()->setStad($persoon['geboorte']['stad']);		
			
			if($persoon['overlijden'] && $persoon['overlijden']['datum']){
				$date = \DateTime::createFromFormat('d/m/Y', $persoon['overlijden']['datum']);
				$persoonEntity->setOverlijden(new Overlijden());
				$persoonEntity->getOverlijden()->setDatum($date);
				$persoonEntity->getOverlijden()->setLand($persoon['overlijden']['land']);
				$persoonEntity->getOverlijden()->setStad($persoon['overlijden']['stad']);
			}			
			
			$manager->persist($persoonEntity);
		}		
		
		$manager->flush();
	}
}
