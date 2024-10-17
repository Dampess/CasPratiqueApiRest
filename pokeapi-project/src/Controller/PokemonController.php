<?php

namespace App\Controller;

use App\Service\PokeApiService;
use App\Entity\Pokemon;
use App\Form\PokemonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PokemonController extends AbstractController
{
    // Service utilisé pour interagir avec l'API Pokémon
    private PokeApiService $pokeApiService;

    // Simule une base de données pour stocker les Pokémon
    private array $pokemons = [];

    // Constructeur pour injecter le service de récupération des données Pokémon
    public function __construct(PokeApiService $pokeApiService)
    {
        $this->pokeApiService = $pokeApiService;
    }

    /**
     * Récupère les données d'un Pokémon par son nom.
     * 
     * @param string $name Le nom du Pokémon à récupérer.
     * @return JsonResponse Les données du Pokémon en format JSON.
     */
    #[Route('/api/pokemons/{name}', name: 'get_pokemon', methods: ['GET'])]
    public function getPokemon(string $name): JsonResponse
    {
        try {
            // Récupération des données du Pokémon via le service
            $data = $this->pokeApiService->fetchPokemonData($name);
            return new JsonResponse($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            // Gestion d'erreur si le Pokémon n'est pas trouvé
            return new JsonResponse(['error' => 'Pokemon not found.'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Récupère tous les Pokémon jusqu'à un nombre spécifié.
     * 
     * @return JsonResponse Liste de tous les Pokémon en format JSON.
     */
    #[Route('/api/pokemons', name: 'get_all_pokemons', methods: ['GET'])]
    public function getAllPokemons(): JsonResponse
    {
        $pokemonsData = [];
        $totalPokemons = 150; // Nombre total de Pokémon à récupérer
        $batchSize = 30; // Taille du lot

        // Récupération par lot de Pokémon
        for ($id = 1; $id <= $totalPokemons; $id++) {
            try {
                // Récupération des données du Pokémon
                $data = $this->pokeApiService->fetchPokemonData($id);
                $pokemonsData[] = [
                    'name' => $data['name'],
                    'type' => $data['types'][0]['type']['name'], // Premier type
                    'abilities' => array_map(fn($ability) => $ability['ability']['name'], $data['abilities']),
                    'image' => $data['sprites']['front_default'],
                ];
            } catch (\Exception $e) {
                // Log des erreurs de récupération de données
                error_log('Error fetching Pokémon with ID ' . $id . ': ' . $e->getMessage());
            }

            // Retourner les données lorsque la taille du lot est atteinte
            if ($id % $batchSize === 0 || $id === $totalPokemons) {
                return new JsonResponse(['hydra:member' => $pokemonsData], Response::HTTP_OK);
            }
        }

        // Retourner tous les Pokémon s'il y en a moins que le lot
        return new JsonResponse(['hydra:member' => $pokemonsData], Response::HTTP_OK);
    }

    /**
     * Affiche le formulaire de modification d'un Pokémon.
     * 
     * @param string $name Le nom du Pokémon à modifier.
     * @return Response La vue du formulaire de modification.
     */
    #[Route('/pokemon/{name}/edit', name: 'pokemon_edit')]
public function edit(string $name, Request $request, PokeApiService $pokeApiService, EntityManagerInterface $entityManager): Response
{
    // Récupérer les données du Pokémon à partir de l'API
    $pokemonData = $pokeApiService->fetchPokemonData($name);

    // Vérifier si le Pokémon existe (vous pourriez vouloir gérer une exception ici)
    if (!$pokemonData) {
        throw $this->createNotFoundException('Pokémon not found');
    }

    // Créer une nouvelle instance de l'entité Pokemon
    $pokemon = new Pokemon();
    $pokemon->setName($pokemonData['name']);
    $pokemon->setType(implode(', ', array_map(fn($type) => $type['type']['name'], $pokemonData['types'])));
    $pokemon->setAbilities(implode(', ', array_map(fn($ability) => $ability['ability']['name'], $pokemonData['abilities'])));

    // Créer le formulaire
    $form = $this->createForm(PokemonType::class, $pokemon);
    $form->handleRequest($request);

    // Gérer la soumission du formulaire
    if ($form->isSubmitted() && $form->isValid()) {
        // Mettre à jour l'entité dans la base de données
        $entityManager->persist($pokemon);

        // Rediriger vers la page de détails du Pokémon
        return $this->redirectToRoute('pokemon', ['name' => $pokemon->getName()]);
    }

    // Rendre la vue du formulaire
    return $this->render('pokemon/edit.html.twig', [
        'form' => $form->createView(),
        'pokemon' => $pokemon,
    ]);
}


    /**
     * Supprime un Pokémon.
     * 
     * @param string $name Le nom du Pokémon à supprimer.
     * @return Response Redirige vers la page d'accueil après la suppression.
     */
    #[Route('/pokemon/{name}/delete', name: 'pokemon_delete', methods: ['POST'])]
    public function delete(string $name): Response
    {
        // Logique pour supprimer le Pokémon
        // À implémenter selon les besoins exemple à la fin avec la methode DELETE
        return $this->redirectToRoute('home');
    }

    /**
     * Recherche un Pokémon par son nom.
     * 
     * @param Request $request La requête contenant le nom du Pokémon.
     * @return Response La vue détaillée du Pokémon ou une erreur.
     */
    #[Route('/pokemon/search', name: 'pokemon_search', methods: ['GET'])]
    public function searchPokemon(Request $request): Response
    {
        $name = $request->query->get('name');

        // Vérifiez que le nom est fourni
        if (!$name) {
            return $this->render('error.html.twig', [
                'message' => 'Veuillez entrer un nom de Pokémon.'
            ]);
        }

        try {
            // Utiliser le service pour récupérer les données
            $pokemonData = $this->pokeApiService->fetchPokemonData($name); 

            return $this->render('pokemon/detail.html.twig', [
                'pokemon' => $pokemonData,
            ]);
        } catch (\Exception $e) {
            // Gestion d'erreur si le Pokémon n'est pas trouvé
            return $this->render('error.html.twig', [
                'message' => 'Pokémon non trouvé.',
            ]);
        }
    }

    /**
     * Affiche les détails d'un Pokémon par son nom.
     * 
     * @param string $name Le nom du Pokémon à afficher.
     * @return Response La vue détaillée du Pokémon ou une erreur.
     */
    #[Route('/pokemon/{name}', name: 'pokemon_detail', methods: ['GET'])]
    public function getPokemonDetail(string $name): Response
    {
        try {
            // Récupération des données du Pokémon
            $pokemonData = $this->pokeApiService->fetchPokemonData($name);

            return $this->render('pokemon/detail.html.twig', [
                'pokemon' => $pokemonData,
            ]);
        } catch (\Exception $e) {
            // Redirection ou affichage d'une page d'erreur si le Pokémon n'est pas trouvé
            return $this->render('error.html.twig', [
                'message' => 'Pokémon non trouvé',
            ]);
        }
    }

    /**
     * Crée un nouveau Pokémon. (Non utilisé ici)
     * 
     * @param Request $request La requête contenant les données du Pokémon.
     * @return JsonResponse Une réponse JSON indiquant le succès ou l'échec.
     */
    #[Route('/api/pokemons', name: 'create_pokemon', methods: ['POST'])]
    public function createPokemon(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $name = $data['name'] ?? null;

        // Vérifiez que le nom est fourni
        if (!$name) {
            return new JsonResponse(['error' => 'Name is required.'], Response::HTTP_BAD_REQUEST);
        }

        // Simuler l'ajout d'un Pokémon dans la "base de données"
        $this->pokemons[] = $name;

        return new JsonResponse(['message' => 'Pokemon created successfully.'], Response::HTTP_CREATED);
    }

    /**
     * Met à jour les données d'un Pokémon existant.
     * 
     * @param string $name Le nom du Pokémon à mettre à jour.
     * @param Request $request La requête contenant les nouvelles données.
     * @return JsonResponse Une réponse JSON indiquant le succès ou l'échec.
     */
    #[Route('/api/pokemons/{name}', name: 'update_pokemon', methods: ['PUT'])]
    public function updatePokemon(string $name, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $newName = $data['name'] ?? null;

        // Vérifiez que le nouveau nom est fourni
        if (!$newName) {
            return new JsonResponse(['error' => 'Name is required.'], Response::HTTP_BAD_REQUEST);
        }

        // Simuler la mise à jour d'un Pokémon
        if (in_array($name, $this->pokemons)) {
            // Remplace le nom du Pokémon dans le tableau
            $this->pokemons[array_search($name, $this->pokemons)] = $newName;
            return new JsonResponse(['message' => 'Pokemon updated successfully.'], Response::HTTP_OK);
        }

        return new JsonResponse(['error' => 'Pokemon not found.'], Response::HTTP_NOT_FOUND);
    }

    /**
     * Supprime un Pokémon existant.
     * 
     * @param string $name Le nom du Pokémon à supprimer.
     * @return JsonResponse Une réponse JSON indiquant le succès ou l'échec.
     */
    #[Route('/api/pokemons/{name}', name: 'delete_pokemon', methods: ['DELETE'])]
    public function deletePokemon(string $name): JsonResponse
    {
        // Simuler la suppression d'un Pokémon
        if (($key = array_search($name, $this->pokemons)) !== false) {
            unset($this->pokemons[$key]);
            return new JsonResponse(['message' => 'Pokemon deleted successfully.'], Response::HTTP_OK);
        }

        return new JsonResponse(['error' => 'Pokemon not found.'], Response::HTTP_NOT_FOUND);
    }
}
