<?php

namespace App\Entity;

interface ImportDatasInterface
{
    public static function createFromCsv(array $datas): self;
}
