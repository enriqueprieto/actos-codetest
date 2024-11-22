@extends('layouts.app')

@section('content')

    <div class="flex justify-between py-4 px-2">
        <h1>Mapa com ArcGIS</h1>
        <div class="flex items-center">
            @auth
                <a href="/painel" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">Painel</a>
                <form id="logout-form" action="/painel/logout" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">
                        Sair
                    </button>
                </form>
            @else
                <a href="/painel/login" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">Entrar</a>
                <a href="/painel/criar" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">Criar conta</a>
            @endauth
        </div>
    </div>


    <div id="mapView" style="width: 100%; height: 500px;"></div>

    <script type="module">
        require([
            "esri/Map",
            "esri/views/MapView",
            "esri/layers/GeoJSONLayer" // Importando o GeoJSONLayer
        ], function (Map, MapView, GeoJSONLayer) {

            // Dados dos layers vindos do backend (exemplo em formato GeoJSON)
            const layersData = @json($layers);
            const centerCoordinates = @json($center); // Passando os dados do Laravel para o JS

            // Criando o mapa
            const map = new Map({
                basemap: "streets-navigation-vector" // Tipo de mapa base
            });

            // Criando a visualização do mapa
            const view = new MapView({
                container: "mapView", // ID do container
                map: map,
                center: centerCoordinates, // Coordenadas iniciais (ex: Brasília, Brasil)
                zoom: 7 // Nível de zoom inicial
            });

            // Adicionando cada layer
            layersData.forEach(layer => {
                if (layer.features && layer.features.length > 0) {
                    const geoJSONLayer = new GeoJSONLayer({
                        url: "data:application/json," + encodeURIComponent(JSON.stringify(layer))
                    });

                    // Adicionando o layer ao mapa
                    map.add(geoJSONLayer);
                }
            });
        });
    </script>
@endsection
