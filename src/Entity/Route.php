<?php

namespace App\Entity;

use App\Repository\RouteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RouteRepository::class)]
class Route implements ImportDatasInterface
{
    #[ORM\Id]
    #[ORM\Column(length: 32, nullable: false)]
    private string $id;

    #[ORM\OneToMany(mappedBy: 'route', targetEntity: Trip::class)]
    private Collection $trips;

    #[ORM\Column(length: 128, nullable: false)]
    private string $shortName;

    #[ORM\Column(length: 255, nullable: false)]
    private string $longName;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private string $description;

    #[ORM\Column(length: 6, nullable: false)]
    private string $color;

    private function __construct(string $id, string $shortName, string $longName, string $description, string $color)
    {
        $this->id = $id;
        $this->trips = new ArrayCollection();
        $this->shortName = $shortName;
        $this->longName = $longName;
        $this->description = $description;
        $this->color = $color;
    }

    public static function createFromCsv(array $datas): self
    {
        return new self($datas['route_id'], $datas['route_short_name'], $datas['route_long_name'], $datas['route_desc'], $datas['route_text_color']);
    }
}
