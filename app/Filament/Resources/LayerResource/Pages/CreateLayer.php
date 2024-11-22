<?php

namespace App\Filament\Resources\LayerResource\Pages;

use App\Filament\Resources\LayerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreateLayer extends CreateRecord
{
    protected static string $resource = LayerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['geometry'])) {
            $filePath = $data['geometry'];

            if (!Storage::exists($filePath)) {
                throw new \Exception('O arquivo GeoJSON não foi encontrado.');
            }

            $fileContent = Storage::get($filePath);

            // Decodificar o JSON
            $geojson = json_decode($fileContent, true);

            if (!isset($geojson['type'])) {
                throw new \Exception('O arquivo não contém um formato válido de GeoJSON.');
            }

            // Adiciona a geometria processada ao formulário
            $data['geometry'] = $this->processGeometry($geojson);
            $data['geometry'] = DB::raw("ST_GeomFromGeoJSON('" . $data['geometry'] . "')");
            // dd($data);

        }

        return $data;
    }

    private function processGeometry(array $geojson): string
    {
        // Caso seja um FeatureCollection ou uma geometria única
        if ($geojson['type'] === 'FeatureCollection') {
            $geometries = collect($geojson['features'])->map(function ($feature) {
                return json_encode($feature['geometry']);
            });
            return $geometries->implode(',');
        }

        // Caso seja uma geometria única
        if (isset($geojson['coordinates'])) {
            return json_encode($geojson);
        }

        throw new \Exception('Formato de GeoJSON não suportado.');
    }
}
