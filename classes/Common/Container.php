<?php

namespace General\Common;

use RuntimeException;

/**
 * A simple dependency injection container.
 */
class Container
{
    /** @var callable[] $factories */
    private array $factories = [];

    /** @var object[] $instances */
    private array $instances = [];

    /**
     * Register a factory for a class.
     *
     * @param string   $id      The class/interface name.
     * @param callable $factory A callable that returns the instance.
     */
    public function set( string $id, callable $factory ): void
    {
        $this->factories[ $id ] = $factory;
    }

    /**
     * Get an instance (creates it once, then reuses).
     *
     * @param string $id The class/interface name.
     *
     * @return object The instance.
     */
    public function get( string $id ): object
    {
        // Bereits instanziiert? Zurückgeben (Singleton-Verhalten)
        if ( isset( $this->instances[ $id ] ) ) return $this->instances[ $id ];

        // Factory vorhanden?
        if ( !isset( $this->factories[ $id ] ) ) {
            throw new RuntimeException( "No factory registered for: $id" );
        }

        // Instanz erstellen und cachen
        $this->instances[ $id ] = $this->factories[ $id ]( $this );

        return $this->instances[ $id ];
    }

    /**
     * Checks whether the specified ID is registered in the container.
     *
     * @param string $id The class/interface name.
     *
     * @return bool
     */
    public function has( string $id ): bool
    {
        return isset( $this->factories[ $id ] ) || isset( $this->instances[ $id ] );
    }
}
