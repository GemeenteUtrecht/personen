<?php

namespace App\Entity\Persoon;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ActivityLogBundle\Entity\Interfaces\StringableInterface;

/**
 * Adres
 * 
 * Beschrijving
 * 
 * @category   	Entity
 *
 * @author     	Ruben van der Linde <ruben@conduction.nl>
 * @license    	EUPL 1.2 https://opensource.org/licenses/EUPL-1.2 *
 * @version    	1.0
 *
 * @link   		http//:www.conduction.nl
 * @package		Commen Ground
 * @subpackage  BRP
 * 
 * @ApiResource
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Naam 
{
	/**
	 * Het identificatie nummer van dit Persoon <br /><b>Schema:</b> <a href="https://schema.org/identifier">https://schema.org/identifier</a>
	 * 
	 * @var int|null
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer", options={"unsigned": true})ss
	 * @ApiProperty(iri="https://schema.org/identifier")
	 */
	public $id;
	
	/**
	 * De naam van dit persoon <br /><b>Schema:</b> <a href="https://schema.org/givenName">https://schema.org/givenName</a>
	 *
	 * @var string
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable = true,
	 * )
	 * @Assert\Length(
	 *      min = 2,
	 *      max = 255,
	 *      minMessage = "De voornaam moet ten minste {{ limit }} karakters lang zijn",
	 *      maxMessage = "De voornaam kan niet langer dan {{ limit }} karakters zijn"
	 * )
	 * @Groups({"read", "write"})
	 * @ApiFilter(SearchFilter::class, strategy="partial")
	 * @ApiFilter(OrderFilter::class)
	 * @ApiProperty(
	 * 	   iri="http://schema.org/name",
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "maxLength"=255,
	 *             "minLength"=2,
	 *             "example"="John"
	 *         }
	 *     }
	 * )
	 **/
	public $voornamen;
	
	/**
	 * De voorletters van deze persoon van een persoon <br /><b>Schema:</b> <a href="https://schema.org/givenName">https://schema.org/givenName</a>
	 *
	 * @var string
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable = true,
	 * )
	 * @Assert\Length(
	 *      min = 2,
	 *      max = 255,
	 *      minMessage = "De voornaam moet ten minste {{ limit }} karakters lang zijn",
	 *      maxMessage = "De voornaam kan niet langer dan {{ limit }} karakters zijn"
	 * )
	 * @Groups({"read", "write"})
	 * @ApiFilter(SearchFilter::class, strategy="partial")
	 * @ApiFilter(OrderFilter::class)
	 * @ApiProperty(
	 * 	   iri="http://schema.org/name",
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "maxLength"=255,
	 *             "example"="J."
	 *         }
	 *     }
	 * )
	 **/
	public $voorletters;
	
	/**
	 * Voorvoegsel van de achternaam <br /><b>Schema:</b> <a href="https://schema.org/givenName">https://schema.org/givenName</a>
	 *
	 * @var string
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable = true,
	 * )
	 * @Assert\Length(
	 *      min = 2,
	 *      max = 255,
	 *      minMessage = "Het voorvoegsel moet ten minste {{ limit }} karakters lang zijn",
	 *      maxMessage = "Het voorvoegsel kan niet langer dan {{ limit }} karakters zijn"
	 * )
	 * @Groups({"read", "write"})
	 * @ApiFilter(SearchFilter::class, strategy="partial")
	 * @ApiFilter(OrderFilter::class)
	 * @ApiProperty(
	 * 	   iri="http://schema.org/name",
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "maxLength"=255,
	 *             "minLength"=2,
	 *             "example"="van der"
	 *         }
	 *     }
	 * )
	 **/
	public $voorvoegselGeslachtsnaam;
	
	/**
	 * De achternaam van dit persoon <br /><b>Schema:</b> <a href="https://schema.org/familyName">https://schema.org/familyName</a>
	 *
	 * @var string
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable = true,
	 * )
	 * @Assert\Length(
	 *      min = 2,
	 *      max = 255,
	 *      minMessage = "De geslachtsnaam moet ten minste {{ limit }} karakters lang zijn",
	 *      maxMessage = "De geslachtsnaam kan niet langer dan {{ limit }} karakters zijn"
	 * )
	 * @Groups({"read", "write"})
	 * @ApiFilter(SearchFilter::class, strategy="partial")
	 * @ApiFilter(OrderFilter::class)
	 * @ApiProperty(
	 * 	   iri="http://schema.org/name",
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "maxLength"=255,
	 *             "minLength"=2,
	 *             "example"="Do"
	 *         }
	 *     }
	 * )
	 **/
	public $geslachtsnaam;
	
	/**
	 * De achternaam van dit persoon <br /><b>Schema:</b> <a href="https://schema.org/familyName">https://schema.org/familyName</a>
	 *
	 * @var string
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255,
	 *     nullable = true,
	 * )
	 * @Assert\Length(
	 *      min = 2,
	 *      max = 255,
	 *      minMessage = "De geslachtsnaam moet ten minste {{ limit }} karakters lang zijn",
	 *      maxMessage = "De geslachtsnaam kan niet langer dan {{ limit }} karakters zijn"
	 * )
	 * @Groups({"read", "write"})
	 * @ApiFilter(SearchFilter::class, strategy="partial")
	 * @ApiFilter(OrderFilter::class)
	 * @ApiProperty(
	 * 	   iri="http://schema.org/name",
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "maxLength"=255,
	 *             "minLength"=2,
	 *             "example"="Do"
	 *         }
	 *     }
	 * )
	 **/
	public $adelijkeTitel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVoornamen(): ?string
    {
        return $this->voornamen;
    }

    public function setVoornamen(?string $voornamen): self
    {
        $this->voornamen = $voornamen;

        return $this;
    }

    public function getVoorletters(): ?string
    {
        return $this->voorletters;
    }

    public function setVoorletters(?string $voorletters): self
    {
        $this->voorletters = $voorletters;

        return $this;
    }

    public function getVoorvoegselGeslachtsnaam(): ?string
    {
        return $this->voorvoegselGeslachtsnaam;
    }

    public function setVoorvoegselGeslachtsnaam(?string $voorvoegselGeslachtsnaam): self
    {
        $this->voorvoegselGeslachtsnaam = $voorvoegselGeslachtsnaam;

        return $this;
    }

    public function getGeslachtsnaam(): ?string
    {
        return $this->geslachtsnaam;
    }

    public function setGeslachtsnaam(?string $geslachtsnaam): self
    {
        $this->geslachtsnaam = $geslachtsnaam;

        return $this;
    }

    public function getAdelijkeTitel(): ?string
    {
        return $this->adelijkeTitel;
    }

    public function setAdelijkeTitel(?string $adelijkeTitel): self
    {
        $this->adelijkeTitel = $adelijkeTitel;

        return $this;
    }	
	
}
