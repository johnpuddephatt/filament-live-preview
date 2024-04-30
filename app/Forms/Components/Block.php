<?php
namespace App\Forms\Components;

use Closure;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Concerns;
use Illuminate\Contracts\Support\Htmlable;

class Block extends Component
{
    use HasAttributes;

    use Concerns\HasName {
        getLabel as getDefaultLabel;
    }

    protected string|Closure|null $icon = null;

    public $incrementing = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * Define that Layout is a model, when in fact it is not.
     *
     * @var bool
     */
    protected $exists = false;

    public function getIncrementing()
    {
        return $this->incrementing;
    }

    /**
     * Get the attributes that should be converted to dates.
     *
     * @return array
     */
    protected function getDates()
    {
        return $this->dates ?? [];
    }

    protected $casts = [];

    final public function __construct(string $name = null, $attributes = [])
    {
        $this->name($name ?? $this->name);
        $this->setRawAttributes($attributes);
    }

    public static function make(string $name = null): static
    {
        $static = app(static::class, ["name" => $name]);
        $static->configure();
        $static->schema($static->fields());

        return $static;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Get the dynamic relation resolver if defined or inherited, or return null.
     * Since it is not possible to define a relation on a layout, this method
     * returns null
     *
     * @param  string  $class
     * @param  string  $key
     * @return mixed
     */
    public function relationResolver($class, $key)
    {
        return null;
    }

    /**
     * Check if relation exists. Layouts do not have relations.
     *
     * @return bool
     */
    protected function relationLoaded()
    {
        return false;
    }

    /**
     * @var array<string, mixed> | null
     */
    protected ?array $labelState = null;

    public function icon(string|Closure|null $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @param  array<string, mixed> | null  $state
     */
    public function labelState(?array $state): static
    {
        $this->labelState = $state;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->evaluate($this->icon);
    }

    public function getLabel(): string|Htmlable
    {
        return $this->evaluate(
            $this->label,
            $this->labelState ? ["state" => $this->labelState] : []
        ) ?? $this->getDefaultLabel();
    }
}
