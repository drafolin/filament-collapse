<?php

namespace Drafolin\FilamentCollapse;

use Closure;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Concerns\HasChildComponents;
use Illuminate\Support\Str;
use InvalidArgumentException;

class FilamentCollapse extends Component
{
    use HasChildComponents;

    public string $view = 'filament-collapse::filament-collapse';

    protected string | Closure $uuid;

    /**
     * @param  array<Component>|Closure  $schema  The child components of the collapse
     */
    final public function __construct(array | Closure $schema)
    {
        $this->uuid = 'collapse__' . Str::uuid();
        $this->schema($schema);
    }

    /**
     * Prepare a new instance of the Collapse
     *
     * @param  array<Component>|Closure  $schema  The child components of the collapse
     *
     * @returns $this The collapse itself
     */
    public static function make(array | Closure $schema): static
    {
        $static = app(static::class, ['schema' => $schema]);
        $static->configure();

        return $static;
    }

    /**
     * Change the uuid of the component, used in the classes names
     *
     * @param  string|Closure  $uuid  The new UUID
     * @return $this The modified Collapse
     *
     * @throws InvalidArgumentException The first character of the UUID must be a letter
     */
    public function uuid(string | Closure $uuid): static
    {
        if (is_string($uuid)) {
            if ($uuid === '') {
                $uuid = 'collapse__' . Str::uuid();
            }

            if (is_numeric($uuid[0])) {
                throw new InvalidArgumentException('The first character of the UUID must not be a number');
            }

            $uuid = Str::replace('-', '_', $uuid);
        }

        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Returns the UUID of the Collapse
     *
     * @return string The UUID
     */
    public function getUuid(): string
    {
        return $this->evaluate($this->uuid);
    }

    public function getChildComponentContainer($key = null): ComponentContainer
    {
        if (filled($key) && array_key_exists($key, $containers = $this->getChildComponentContainers())) {
            return $containers[$key];
        }

        $childComponents = $this->getChildComponents();

        return ComponentContainer::make($this->getLivewire())
            ->parentComponent($this)
            ->components(collect($childComponents)
            ->map(
                fn(Component $child, int $id) => $child
                    ->extraAttributes([
                        'class' => 'filament-collapse__collapse-item'
                    ], true)
                    ->when(
                        $id === 0,
                        fn($child) => $child->extraAttributes([
                            'class' => 'filament-collapse__collapse-item-first'
                        ], true),
                    )
                    ->when(
                        $id === count($childComponents) - 1,
                        fn($child) => $child->extraAttributes([
                            'class' => 'filament-collapse__collapse-item-last'
                        ], true),
                    )
                    ->when(
                        !$child->isLabelHidden(),
                        fn($child) => $child->placeholder($child->getLabel())
                    )
                    ->hiddenLabel()
                )
                ->toArray()
            );
    }
}
