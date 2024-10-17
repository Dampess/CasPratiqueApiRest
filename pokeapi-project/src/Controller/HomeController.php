<?php

namespace App\Controller;

use App\Service\PokeApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    // Service utilisé pour interagir avec l'API Pokémon
    private PokeApiService $pokeApiService;

    /**
     * Constructeur pour injecter le service PokeApiService.
     *
     * @param PokeApiService $pokeApiService Service pour récupérer les données des Pokémon
     */
    public function __construct(PokeApiService $pokeApiService)
    {
        $this->pokeApiService = $pokeApiService;
    }

    /**
     * Affiche la page d'accueil.
     *
     * @return Response La réponse contenant le rendu de la vue d'accueil
     */
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * Affiche les détails d'un Pokémon spécifique.
     *
     * @param string $name Le nom du Pokémon à afficher
     * @return Response La réponse contenant le rendu de la vue de détails du Pokémon
     */
    #[Route('/pokemon/{name}', name: 'pokemon')]
    public function show(string $name): Response
    {
        // Récupère les données du Pokémon en utilisant le service
        $pokemonData = $this->pokeApiService->fetchPokemonData($name);

        // Rendu de la vue avec les données du Pokémon
        return $this->render('pokemon/detail.html.twig', [
            'name' => $name, // Nom du Pokémon passé à la vue
            'pokemon' => $pokemonData // Données du Pokémon passées à la vue
        ]);
    }
}
