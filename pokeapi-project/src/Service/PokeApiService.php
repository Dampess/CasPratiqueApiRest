<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class PokeApiService
{
    // Client HTTP utilisé pour effectuer des requêtes API
    private HttpClientInterface $client;

    // Constructeur pour injecter le client HTTP
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Récupère les données d'un Pokémon par son nom ou son ID.
     *
     * @param string|int $identifier Nom ou ID du Pokémon
     * @return array Données du Pokémon
     * @throws \Exception En cas d'erreur lors de la récupération des données
     */
    public function fetchPokemonData(string|int $identifier): array
    {
        // Construire l'URL en fonction de si l'identifiant est un nom ou un ID
        $url = "https://pokeapi.co/api/v2/pokemon/{$identifier}"; // L'URL est identique pour les deux cas

        // Effectuer la requête GET pour récupérer les données du Pokémon
        $response = $this->client->request('GET', $url);

        // Retourner les données sous forme de tableau
        return $response->toArray();
    }
}
