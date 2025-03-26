<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultipleRankingsExport implements WithMultipleSheets
{
    protected $rankingsEntrada;
    protected $rankingsSalida;
    protected $title;

    public function __construct($rankingsEntrada, $rankingsSalida, $title)
    {
        $this->rankingsEntrada = $rankingsEntrada;
        $this->rankingsSalida = $rankingsSalida;
        $this->title = $title;
    }

    public function sheets(): array
    {
        return [
            'Ranking de Entrada' => new RankingsExport($this->rankingsEntrada, $this->title, 'entrada'),
            'Ranking de Salida' => new RankingsExport($this->rankingsSalida, $this->title, 'salida')
        ];
    }
}
