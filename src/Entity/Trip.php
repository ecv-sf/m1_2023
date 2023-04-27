<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TripRepository::class)]
class Trip implements ImportDatasInterface
{
    #[ORM\Id]
    #[ORM\Column(length: 64, nullable: false)]
    private string $id;

    #[ORM\ManyToOne(inversedBy: 'trips')]
    private Route $route;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: StopTime::class)]
    private Collection $stopTimes;

    #[ORM\Column(length: 128, nullable: false)]
    private string $headsign;

    #[ORM\Column(type: Types::SMALLINT, nullable: false)]
    private int $direction;

    #[ORM\Column(type: Types::BOOLEAN, nullable: false)]
    private bool $isWheelchairAccessible;

    private function __construct(string $id, Route $route, string $headsign, string $direction, bool $isWheelchairAccessible)
    {
        $this->id = $id;
        $this->route = $route;
        $this->stopTimes = new ArrayCollection();
        $this->headsign = $headsign;
        $this->direction = $direction;
        $this->isWheelchairAccessible = $isWheelchairAccessible;
    }

    public static function createFromCsv(array $datas): self
    {
        return new self($datas['trip_id'], $datas['route'], $datas['trip_headsign'], $datas['direction_id'], 1 === $datas['wheelchair_accessible']);
    }
}
