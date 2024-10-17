<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ApiResource(
    collectionOperations: ['get', 'post'], // Opérations disponibles sur la collection de Pokémon
    itemOperations: ['get', 'put', 'delete'] // Opérations disponibles sur un Pokémon individuel
)]
class Pokemon
{
    // Identifiant unique du Pokémon (clé primaire)
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    // Nom du Pokémon
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name = null;

    // Type du Pokémon (ex: feu, eau, etc.)
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $type = null;

    // Capacités du Pokémon (ex: charge, saut, etc.)
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $abilities = null;

    // Getters et Setters

    /**
     * Récupère l'identifiant du Pokémon.
     *
     * @return int|null L'identifiant du Pokémon
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Récupère le nom du Pokémon.
     *
     * @return string|null Le nom du Pokémon
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Définit le nom du Pokémon.
     *
     * @param string $name Le nom à définir pour le Pokémon
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Récupère le type du Pokémon.
     *
     * @return string|null Le type du Pokémon
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Définit le type du Pokémon.
     *
     * @param string $type Le type à définir pour le Pokémon
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Récupère les capacités du Pokémon.
     *
     * @return string|null Les capacités du Pokémon
     */
    public function getAbilities(): ?string
    {
        return $this->abilities;
    }

    /**
     * Définit les capacités du Pokémon.
     *
     * @param string $abilities Les capacités à définir pour le Pokémon
     * @return self
     */
    public function setAbilities(string $abilities): self
    {
        $this->abilities = $abilities;
        return $this;
    }
}
