<?php

namespace App\Entity;

use App\Entity\Persoon\Adres;
use App\Entity\Persoon\Geboorte;
use App\Entity\Persoon\Naam;
use App\Entity\Persoon\Overlijden;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;
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
 * Persoon
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
 * @ApiResource(
 *  collectionOperations={
 *  	"get"={
 *  		"normalizationContext"={"groups"={"persoon:lezen"},"enable_max_depth" = true, "circular_reference_handler"},
 *  		"denormalizationContext"={"groups"={"persoon:schrijven"},"enable_max_depth" = true, "circular_reference_handler"},
 *      	"path"="/personen",
 *  		"openapi_context" = {
 * 				"summary" = "Verzameling",
 *         		"description" = "Haal een verzameling van Applicaties op, het is mogelijk om deze resultaten te filteren aan de hand van query parameters. <br><br>Lees meer over het filteren van resultaten onder [filteren](/#section/Filteren)."            
 *  		}
 *  	},
 *     "post"={
 *  		"normalizationContext"={"groups"={"persoon:lezen"},"enable_max_depth" = true, "circular_reference_handler"},
 *  		"denormalizationContext"={"groups"={"persoon:schrijven"},"enable_max_depth" = true, "circular_reference_handler"},
 *      	"path"="/personen",
 *  		"openapi_context" = {
 * 				"summary" = "Maak aan",
 *         		"description" = "Maak een persoon aan."
 *  		}
 *  	},
 *  },
 * 	itemOperations={
 *     "get"={
 *  		"normalizationContext"={"groups"={"persoon:lezen"},"enable_max_depth" = true, "circular_reference_handler"},
 *  		"denormalizationContext"={"groups"={"persoon:schrijven"},"enable_max_depth" = true, "circular_reference_handler"},
 *      	"path"="/persoon/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Haal op",
 *         		"description" = "Haal een persoon op."           
 *  		}
 *  	},
 *     "put"={
 *  		"normalizationContext"={"groups"={"persoon:lezen"},"enable_max_depth" = true, "circular_reference_handler"},
 *  		"denormalizationContext"={"groups"={"persoon:schrijven"},"enable_max_depth" = true, "circular_reference_handler"},
 *      	"path"="/persoon/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Werk bij",
 *         		"description" = "Werk een persoon bij."
 *  		}
 *  	},
 *     "delete"={
 *  		"normalizationContext"={"groups"={"persoon:lezen"},"enable_max_depth" = true, "circular_reference_handler"},
 *  		"denormalizationContext"={"groups"={"persoon:verwijderen"},"enable_max_depth" = true, "circular_reference_handler"},
 *      	"path"="/persoon/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Verwijder een specifiek persoon"
 *  		}
 *  	},
 *     "log"={
 *         	"method"="GET",
 *         	"path"="/persoon/{id}/log",
 *          "controller"= UserController::class,
 *     		"normalization_context"={"groups"={"persoon:lezen"},"enable_max_depth" = true, "circular_reference_handler"},
 *     		"denormalization_context"={"groups"={"persoon:schrijven"},"enable_max_depth" = true, "circular_reference_handler"},
 *         	"openapi_context" = {
 *         		"summary" = "Logboek",
 *         		"description" = "Bekijk de wijzigingen op dit persoon object."
 *         }
 *     }
 *  }
 * )
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable(logEntryClass="ActivityLogBundle\Entity\LogEntry")
 * @ORM\HasLifecycleCallbacks
 * @ApiFilter(DateFilter::class, properties={"geboren.datum","overlijden.datum"})
 * @UniqueEntity(
 *     fields={"identificatie", "bronOrganisatie"},
 *     message="De identificatie dient uniek te zijn voor de bronOrganisatie"
 * )
 */
class Persoon implements StringableInterface
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ApiFilter(SearchFilter::class, strategy="exact")
	 * @ApiFilter(OrderFilter::class)
	 * @Groups({"persoon:lezen"})
	 */
	public $id;
	
	/**
	 * De unieke identificatie van dit object binnen de organisatie die dit object heeft gecreeerd. <br /><b>Schema:</b> <a href="https://schema.org/identifier">https://schema.org/identifier</a>
	 *
	 * @var string
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 40,
	 *     nullable=true
	 * )
	 * @Assert\Length(
	 *      max = 40,
	 *      maxMessage = "Het RSIN kan niet langer dan {{ limit }} karakters zijn"
	 * )
	 * @Groups({"persoon:lezen", "persoon:schrijven"})
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="6a36c2c4-213e-4348-a467-dfa3a30f64aa",
	 *             "description"="De unieke identificatie van dit object de organisatie die dit object heeft gecreeerd.",
	 *             "maxLength"=40
	 *         }
	 *     }
	 * )
	 * @Gedmo\Versioned
	 */
	public $identificatie;
	
	/**
	 * Het RSIN van de organisatie waartoe deze Persoon behoord. Dit moet een geldig RSIN zijn van 9 nummers en voldoen aan https://nl.wikipedia.org/wiki/Burgerservicenummer#11-proef. <br> Het RSIN word bepaald aan de hand van de gauthenticeerde applicatie en kan niet worden overschreven
	 *
	 * @var integer
	 * @ORM\Column(
	 *     type     = "integer",
	 *     length   = 9
	 * )
	 * @Assert\Length(
	 *      min = 8,
	 *      max = 9,
	 *      minMessage = "Het RSIN moet ten minste {{ limit }} karakters lang zijn",
	 *      maxMessage = "Het RSIN kan niet langer dan {{ limit }} karakters zijn"
	 * )
	 * @Groups({"persoon:lezen"})
	 * @ApiFilter(SearchFilter::class, strategy="exact")
	 * @ApiFilter(OrderFilter::class)
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "title"="bronOrganisatie",
	 *             "type"="string",
	 *             "example"="123456789",
	 *             "required"="true",
	 *             "maxLength"=9,
	 *             "minLength"=8
	 *         }
	 *     }
	 * )
	 */
	public $bronOrganisatie;
	
	/**
	 * Een burger service nummer
	 *
	 * @var string
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 40,
	 *     nullable=true
	 * )
	 * @Assert\Length(
	 *      max = 40,
	 *      maxMessage = "Het BSN kan niet langer dan {{ limit }} karakters zijn"
	 * )
	 * @Groups({"persoon:lezen", "persoon:schrijven"})
     * @ApiFilter(SearchFilter::class, strategy="exact")
     * @ApiFilter(OrderFilter::class)
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="123456789",
	 *             "description"="Een burger service nummer",
	 *             "maxLength"=40
	 *         }
	 *     }
	 * )
	 * @Gedmo\Versioned
	 */
	public $burgerservicenummer;
	
	/**
	 * Het email adres van dit persoon <br /><b>Schema:</b> <a href="https://schema.org/email">https://schema.org/email</a>
	 *
	 * @var string
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255, 
	 *     nullable = true,
	 * )
	 * @Assert\Email(
     *     message = "Het email addres '{{ value }}' is geen geldig email addres.",
     *     checkMX = true
     * )
	 * @Assert\Length(
	 *      min = 8,
	 *      max = 255,
	 *      minMessage = "Het email addres moet minimaal  {{ limit }} tekens lang zijn",
	 *      maxMessage = "Het email addresm mag maximaal {{ limit }} tekens lang zijn"
	 * )
	 * @Groups({"persoon:lezen", "persoon:schrijven"})
     * @ApiFilter(SearchFilter::class, strategy="partial")
     * @ApiFilter(OrderFilter::class)
	 * @ApiProperty(
	 * 	   iri="http://schema.org/name",
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="email",
	 *             "maxLength"=255,
	 *             "minLength"=8,
	 *             "example"="john@do.nl"
	 *         }
	 *     }
	 * )
	 * @Gedmo\Versioned
	 **/
	public $emailadres;
	
	/**
	 * Het telefoon nummer van dit persoon <br /><b>Schema:</b> <a href="https://schema.org/telephone">https://schema.org/telephone</a>
	 *
	 * @var string
	 *
	 * @Assert\Length(
	 *      min = 10,
	 *      max = 255,
	 *      minMessage = "Het telefoonnummer moet minimaal {{ limit }} tekens lang zijn",
	 *      maxMessage = "Het telefoonnummer mag maximaal {{ limit }} tekens lang zijn"
	 * )
	 * @Groups({"persoon:lezen", "persoon:schrijven"})
     * @ApiFilter(SearchFilter::class, strategy="partial")
     * @ApiFilter(OrderFilter::class)
	 * @ApiProperty(
	 * 	   iri="http://schema.org/name",
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "maxLength"=255,
	 *             "minLength"=10,
	 *             "example"="+31(0)6-12345678"
	 *         }
	 *     }
	 * )
	 * @Gedmo\Versioned
	 **/
	public $telefoonnummer;
	
	/**
	 * Het fax nummer van dit persoon <br /><b>Schema:</b> <a href="https://schema.org/telephone">https://schema.org/telephone</a>
	 *
	 * @var string
	 *
	 * @Assert\Length(
	 *      min = 10,
	 *      max = 255,
	 *      minMessage = "Het telefoonnummer moet minimaal {{ limit }} tekens lang zijn",
	 *      maxMessage = "Het telefoonnummer mag maximaal {{ limit }} tekens lang zijn"
	 * )
	 * @Groups({"persoon:lezen", "persoon:schrijven"})
     * @ApiFilter(SearchFilter::class, strategy="partial")
     * @ApiFilter(OrderFilter::class)
	 * @ApiProperty(
	 * 	   iri="http://schema.org/name",
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "maxLength"=255,
	 *             "minLength"=10,
	 *             "example"="+31(0)20-1234567"
	 *         }
	 *     }
	 * )
	 * @Gedmo\Versioned
	 **/
	public $faxNummer;
	
	/**
	 * De website van deze persoon
	 *
	 * @var string
	 *
	 * @Assert\Length(
	 *      min = 10,
	 *      max = 255,
	 *      minMessage = "Het telefoonnummer moet minimaal {{ limit }} tekens lang zijn",
	 *      maxMessage = "Het telefoonnummer mag maximaal {{ limit }} tekens lang zijn"
	 * )
	 * @Groups({"persoon:lezen", "persoon:schrijven"})
	 * @ApiProperty(
	 * 	   iri="http://schema.org/name",
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "maxLength"=255,
	 *             "minLength"=10,
	 *             "example"="www.john.do"
	 *         }
	 *     }
	 * )
	 * @Gedmo\Versioned
	 **/
	public $websiteUrl;
	
	/**
	 * De aanduiding naasgebruik (voorheen geslacht) van deze persoon
	 *
	 * @var string
	 *
	 * @Assert\Length(
	 *      min = 10,
	 *      max = 255,
	 *      minMessage = "Het telefoonnummer moet minimaal {{ limit }} tekens lang zijn",
	 *      maxMessage = "Het telefoonnummer mag maximaal {{ limit }} tekens lang zijn"
	 * )
	 * @Groups({"persoon:lezen", "persoon:schrijven"})
	 * @ApiProperty(
	 * 	   iri="http://schema.org/name",
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "maxLength"=255,
	 *             "minLength"=10,
	 *             "example"="v"
	 *         }
	 *     }
	 * )
	 * @Gedmo\Versioned
	 **/
	public $aanduidingNaamsgebruik;
	
	/**
	 * @var Array Verwijzing naar de BRP inschrijving van ouders
	 *
	 * @ORM\Column(type="json", nullable=true)
	 * @Groups({"persoon:lezen", "persoon:schrijven"})
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="[]"
	 *         }
	 *     }
	 * )
	 */
	public $ouders;
	
	/**
	 * @var Array Verwijzing naar de BRP inschrijving van partners
	 *
	 * @ORM\Column(type="json", nullable=true)
	 * @Groups({"persoon:lezen", "persoon:schrijven"})
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="[]"
	 *         }
	 *     }
	 * )
	 */
	public $partners;
	
	/**
	 * @var Array Verwijzing naar de BRP inschrijving van kinderen
	 *
	 * @ORM\Column(type="json", nullable=true)
	 * @Groups({"persoon:lezen", "persoon:schrijven"})
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="[]"
	 *         }
	 *     }
	 * )
	 */
	public $kinderen;	
	
	/**
	 * @ApiSubresource(maxDepth=1)
	 * @Groups({"persoon:lezen", "persoon:schrijven"})
	 * @ORM\ManyToOne(targetEntity="App\Entity\Persoon\Naam",cascade={"persist"})
	 * @ORM\JoinColumn( referencedColumnName="id", nullable=true)
	 */
	private $aanschrijving;
	
	/**
	 * @ApiSubresource(maxDepth=1)
	 * @Groups({"persoon:lezen", "persoon:schrijven"})
	 * @ORM\ManyToOne(targetEntity="App\Entity\Persoon\Naam",cascade={"persist"})
	 * @ORM\JoinColumn( referencedColumnName="id", nullable=true)
	 */
	private $naam;
	
	/**
	 * @ApiSubresource(maxDepth=1)
	 * @Groups({"persoon:lezen", "persoon:schrijven"})
	 * @ORM\ManyToOne(targetEntity="App\Entity\Persoon\Adres",cascade={"persist"})
	 * @ORM\JoinColumn( referencedColumnName="id", nullable=true)
	 */
	private $verblijfadres;
		
	/*** 
	 * @ApiSubresource(maxDepth=1)
	 * @Groups({"persoon:lezen", "persoon:schrijven"})
	 * @ORM\ManyToOne(targetEntity="App\Entity\Persoon\Adres",cascade={"persist"})
	 * @ORM\JoinColumn( referencedColumnName="id", nullable=true)
	 */
	private $postadres;
	
	/**
	 * @ApiSubresource(maxDepth=1)
	 * @Groups({"persoon:lezen", "persoon:schrijven"})
	 * @ORM\OneToOne(targetEntity="App\Entity\Persoon\Geboorte", cascade={"persist"})
	 * @ORM\JoinColumn( referencedColumnName="id", nullable=true)
	 */
	private $geboorte;
	
	/**
	 * @ApiSubresource(maxDepth=1)
	 * @Groups({"persoon:lezen", "persoon:schrijven"})
	 * @ORM\OneToOne(targetEntity="App\Entity\Persoon\Overlijden", cascade={"persist"})
	 * @ORM\JoinColumn( referencedColumnName="id", nullable=true)
	 */
	private $overlijden;
	
	/**
	 * URL-referentie naar de agenda van dit persoon
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     nullable = true
	 * )
	 * @Groups({"persoon:lezen", "persoon:schrijven"})
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="url",
	 *             "example"="https://ref.tst.vng.cloud/zrc/api/v1/zaken/24524f1c-1c14-4801-9535-22007b8d1b65",
	 *             "required"="true",
	 *             "maxLength"=255,
	 *             "format"="uri",
	 *             "description"="URL-referentie naar de agenda van deze dit persoon"
	 *         }
	 *     }
	 * )
	 * @Gedmo\Versioned
	 */
	public $agenda;
		
	/**
	 * De taal waarin de informatie van  dit object is opgesteld <br /><b>Schema:</b> <a href="https://www.ietf.org/rfc/rfc3066.txt">https://www.ietf.org/rfc/rfc3066.txt</a>
	 *
	 * @var string Een Unicode language identifier, ofwel RFC 3066 taalcode.
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 17
	 * )
	 * @Groups({"persoon:lezen", "persoon:schrijven"})
	 * @Assert\Language
	 * @Assert\Length(
	 *      min = 2,
	 *      max = 17,
	 *      minMessage = "De taal moet ten minste {{ limit }} karakters lang zijn",
	 *      maxMessage = "De taal kan niet langer dan {{ limit }} karakters zijn"
	 * )
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "maxLength"=17,
	 *             "minLength"=2,
	 *             "example"="NL"
	 *         }
	 *     }
	 * )
	 * @Gedmo\Versioned
	 **/
	public $taal = 'nl';
	
	/**
	 * Het tijdstip waarop dit Persoon object is aangemaakt
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"
	 * @Gedmo\Timestampable(on="create")
	 * @Assert\DateTime
	 * @ORM\Column(
	 *     type     = "datetime"
	 * )
	 * @Groups({"persoon:lezen"})
	 */
	public $registratiedatum;
	
	/**
	 * Het tijdstip waarop dit Persoon object voor het laatst is gewijzigd.
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"
	 * @Gedmo\Timestampable(on="update")
	 * @Assert\DateTime
	 * @ORM\Column(
	 *     type     = "datetime",
	 *     nullable	= true
	 * )
	 * @Groups({"persoon:lezen"})
	 */
	public $wijzigingsdatum;
	
	/**
	 * Het contact persoon voor deze Persoon
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     nullable = true
	 * )
	 * @Groups({"persoon:lezen", "persoon:schrijven"})
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "title"="Contactpersoon",
	 *             "type"="url",
	 *             "example"="https://ref.tst.vng.cloud/zrc/api/v1/zaken/24524f1c-1c14-4801-9535-22007b8d1b65",
	 *             "required"="true",
	 *             "maxLength"=255,
	 *             "format"="uri"
	 *         }
	 *     }
	 * )
	 * @Gedmo\Versioned
	 */
	public $contactPersoon;
	
	/**
	 * Met eigenaar wordt bijgehouden welke  applicatie verantwoordelijk is voor het object, en daarvoor de rechten beheerd en uitgeeft. In die zin moet de eigenaar dan ook worden gezien in de trant van autorisatie en configuratie in plaats van als onderdeel van het datamodel.
	 *
	 * @var App\Entity\Applicatie $eigenaar
	 *
	 * @Gedmo\Blameable(on="create")
	 * @ORM\ManyToOne(targetEntity="App\Entity\Applicatie")
	 * @Groups({"persoon:lezen"})
	 */
	public $eigenaar;
	
	/**
	 * @return string
	 */
	public function toString()
	{
		// If there is a voorvoegselGeslachtsnaam we want to add a save between voorvoegselGeslachtsnaam and geslachtsnaam;
		$voorvoegselGeslachtsnaam = $this->getNaam()->getVoorvoegselGeslachtsnaam();
		if($voorvoegselGeslachtsnaam){$voorvoegselGeslachtsnaam=$voorvoegselGeslachtsnaam.' ';}
		// Lets render the name
		return $this->getNaam()->getVoornamen().$voorvoegselGeslachtsnaam.$this->getNaam()->getGeslachtsnaam();
	}
	
	/**
	 * Vanuit rendering perspectief (voor bijvoorbeeld loging of berichten) is het belangrijk dat we een entiteit altijd naar string kunnen omzetten
	 */
	public function __toString()
	{
		return $this->toString();
	}
	
	public function getEmailadres(): ?string
    {
        return $this->emailadres;
    }

    public function setEmailadres(?string $emailadres): self
    {
        $this->emailadres = $emailadres;

        return $this;
    }
	
	public function getTelefoonnummer()
	{
		return $this->telefoonnummer;
	}
	
	public function setTelefoonnummer($telefoonnummer)
	{
		$this->telefoonnummer = $telefoonnummer;
	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentificatie(): ?string
    {
        return $this->identificatie;
    }

    public function setIdentificatie(?string $identificatie): self
    {
        $this->identificatie = $identificatie;

        return $this;
    }

    public function getBronOrganisatie(): ?int
    {
        return $this->bronOrganisatie;
    }

    public function setBronOrganisatie(int $bronOrganisatie): self
    {
        $this->bronOrganisatie = $bronOrganisatie;

        return $this;
    }

    public function getBurgerservicenummer(): ?string
    {
        return $this->burgerservicenummer;
    }

    public function setBurgerservicenummer(?string $burgerservicenummer): self
    {
        $this->burgerservicenummer = $burgerservicenummer;

        return $this;
    }

    public function getAgenda(): ?string
    {
        return $this->agenda;
    }

    public function setAgenda(?string $agenda): self
    {
        $this->agenda = $agenda;

        return $this;
    }

    public function getTaal(): ?string
    {
        return $this->taal;
    }

    public function setTaal(string $taal): self
    {
        $this->taal = $taal;

        return $this;
    }

    public function getRegistratiedatum(): ?\DateTimeInterface
    {
        return $this->registratiedatum;
    }

    public function setRegistratiedatum(\DateTimeInterface $registratiedatum): self
    {
        $this->registratiedatum = $registratiedatum;

        return $this;
    }

    public function getWijzigingsdatum(): ?\DateTimeInterface
    {
        return $this->wijzigingsdatum;
    }

    public function setWijzigingsdatum(?\DateTimeInterface $wijzigingsdatum): self
    {
        $this->wijzigingsdatum = $wijzigingsdatum;

        return $this;
    }

    public function getContactPersoon(): ?string
    {
        return $this->contactPersoon;
    }

    public function setContactPersoon(?string $contactPersoon): self
    {
        $this->contactPersoon = $contactPersoon;

        return $this;
    }

    public function getVerblijfadres(): ?Adres
    {
        return $this->verblijfadres;
    }

    public function setVerblijfadres(?Adres $verblijfadres): self
    {
        $this->verblijfadres = $verblijfadres;

        return $this;
    }

    public function getPostadres(): ?Adres
    {
        return $this->postadres;
    }

    public function setPostadres(?Adres $postadres): self
    {
        $this->postadres = $postadres;

        return $this;
    }

    public function getGeboorte(): ?Geboorte
    {
        return $this->geboorte;
    }

    public function setGeboorte(Geboorte $geboorte): self
    {
        $this->geboorte = $geboorte;

        return $this;
    }

    public function getOverlijden(): ?Overlijden
    {
        return $this->overlijden;
    }

    public function setOverlijden(Overlijden $overlijden): self
    {
        $this->overlijden = $overlijden;

        return $this;
    }

    public function getEigenaar(): ?Applicatie
    {
        return $this->eigenaar;
    }

    public function setEigenaar(?Applicatie $eigenaar): self
    {
        $this->eigenaar = $eigenaar;

        return $this;
    }

    public function getOuders(): ?array
    {
        return $this->ouders;
    }

    public function setOuders(?array $ouders): self
    {
        $this->ouders = $ouders;

        return $this;
    }

    public function getPartners(): ?array
    {
        return $this->partners;
    }

    public function setPartners(?array $partners): self
    {
        $this->partners = $partners;

        return $this;
    }

    public function getKinderen(): ?array
    {
        return $this->kinderen;
    }

    public function setKinderen(?array $kinderen): self
    {
        $this->kinderen = $kinderen;

        return $this;
    }

    public function getAanschrijving(): ?Naam
    {
        return $this->aanschrijving;
    }

    public function setAanschrijving(?Naam $aanschrijving): self
    {
        $this->aanschrijving = $aanschrijving;

        return $this;
    }

    public function getNaam(): ?Naam
    {
        return $this->naam;
    }

    public function setNaam(?Naam $naam): self
    {
        $this->naam = $naam;

        return $this;
    }	
	
	
}
