<?php

namespace App\Entity;

use App\Repository\StopRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StopRepository::class)]
class Stop implements ImportDatasInterface
{
    #[ORM\Id]
    #[ORM\Column(length: 32, nullable: false)]
    private string $id;

    #[ORM\OneToMany(mappedBy: 'stop', targetEntity: StopTime::class)]
    private Collection $stopTimes;

    #[ORM\Column(length: 128, nullable: false)]
    private string $name;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private string $description;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 7)]
    private float|null $latitude;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 7)]
    private float|null $longitude;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $locationType;

    #[ORM\ManyToOne(targetEntity: self::class)]
    private ?self $parent;

    #[ORM\Column(type: Types::BOOLEAN, nullable: false)]
    private bool $isWheelchairBoarding;

    public function __construct(string $id, string $name, string $description, float $latitude, float $longitude, int $locationType, ?self $parent, bool $wheelchairBoarding)
    {
        $this->id = $id;
        $this->stopTimes = new ArrayCollection();
        $this->name = $name;
        $this->description = $description;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->locationType = $locationType;
        $this->parent = $parent;
        $this->isWheelchairBoarding = $wheelchairBoarding;
    }

    public static function createFromCsv(array $datas): self
    {
        return new self($datas['stop_id'], $datas['stop_name'], $datas['stop_desc'], $datas['stop_lat'], $datas['stop_lon'], $datas['location_type'], $datas['parent'], $datas['wheelchair_boarding']);
    }
}
