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
class Adres 
{
	/**
	 * Het identificatie nummer van dit Persoon <br /><b>Schema:</b> <a href="https://schema.org/identifier">https://schema.org/identifier</a>
	 * 
	 * @var int|null
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer", options={"unsigned": true})
	 * @ApiProperty(iri="https://schema.org/identifier")
	 */
	public $id;
		
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
	public $straatnaam;
	
	
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
	public $huisnummer;
		
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
	public $postcode;
		
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
	public $woonplaats;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStraatnaam(): ?string
    {
        return $this->straatnaam;
    }

    public function setStraatnaam(?string $straatnaam): self
    {
        $this->straatnaam = $straatnaam;

        return $this;
    }

    public function getHuisnummer(): ?string
    {
        return $this->huisnummer;
    }

    public function setHuisnummer(?string $huisnummer): self
    {
        $this->huisnummer = $huisnummer;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(?string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getWoonplaats(): ?string
    {
        return $this->woonplaats;
    }

    public function setWoonplaats(?string $woonplaats): self
    {
        $this->woonplaats = $woonplaats;

        return $this;
    }
}
