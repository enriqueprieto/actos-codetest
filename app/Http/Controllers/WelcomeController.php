<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        // Consultar as layers e converter a geometria para GeoJSON
        $layers = [];
        $centerCoordinates = [-47.9292, -15.7801];

        if (Auth::check()) {
            $layers = DB::table('layers')
                ->select('id', 'name', DB::raw('ST_AsGeoJSON(geometry) AS geometry'))
                ->get()
                ->map(function ($layer) {
                    return [
                        'id' => $layer->id,
                        'name' => $layer->name,
                        'geometry' => json_decode($layer->geometry),
                    ];
                })
                ->map(function ($layer) {
                    // Aqui criamos a estrutura de um FeatureCollection
                    return [
                        'type' => 'FeatureCollection',
                        'features' => [
                            [
                                'type' => 'Feature',
                                'geometry' => $layer['geometry'], // A geometria original
                                'properties' => [
                                    'name' => $layer['name'],
                                ],
                            ],
                        ],
                    ];
                });

            $firstLayer = $layers->first();
            $firstPolygonCoordinates = $firstLayer['features'][0]['geometry']->coordinates[0];
            $centerCoordinates = $firstPolygonCoordinates[0];
        }

        return view('welcome', ['layers' => $layers, 'center' => $centerCoordinates]);
    }
}
