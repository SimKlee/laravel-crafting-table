<?php /** @var \SimKlee\LaravelCraftingTable\Models\ModelDefinition $modelDefinition */ ?>
<?php /** @var \SimKlee\LaravelCraftingTable\Generators\AbstractGenerator $generator */ ?>
declare(strict_types=1);

namespace {{ $generator->getNamespace() }};

@foreach($generator->getUses() as $class)
use {{ $class }};
@endforeach

/**
 * // Properties
@foreach($modelDefinition->columns as $columnDefinition)
 * {{ '@' }}property {{ $columnDefinition->dataTypeCast }} ${{ $columnDefinition->name }}
@endforeach
 *
 * // Relations
@foreach($modelDefinition->getColumnsWithForeignKey() as $columnDefinition)
 * {{ '@' }}property {{ $columnDefinition->foreignKeyModel }} ${{ Str::camel($columnDefinition->foreignKeyModel) }}
@endforeach
 *
 * // Methods
 * {{ '@' }}method static {{ $modelDefinition->model }}Query query()
 * {{ '@' }}method static {{ $modelDefinition->model }} createFake(array $attributes)
 * {{ '@' }}method static {{ $modelDefinition->model }} makeFake(array $attributes)
 * {{ '@' }}method static Collection|{{ $modelDefinition->model }}[] createFakes(int $count, array $attributes)
 * {{ '@' }}method static Collection|{{ $modelDefinition->model }}[] makeFakes(int $count, array $attributes)
 */
class {{ $modelDefinition->model }} extends {{ $generator->getExtends() }}
{
@foreach($modelDefinition->traits as $trait)
    use {{ $trait }};
@endforeach

    public const TABLE = '{{ $modelDefinition->table }}';

<?php /** @var \SimKlee\LaravelCraftingTable\Models\ColumnDefinition $columnDefinition */ ?>
@foreach($modelDefinition->columns as $columnDefinition)
    public const PROPERTY_{{ Str::upper($columnDefinition->name) }} = '{{ $columnDefinition->name }}';
@endforeach

@foreach($modelDefinition->getColumnsWithForeignKey() as $columnDefinition)
    public const WITH_{{ Str::upper(Str::snake($columnDefinition->foreignKeyModel)) }} = '{{ Str::lower(Str::snake($columnDefinition->foreignKeyModel)) }}';
@endforeach

    /** {{ '@' }}var string */
    protected $table = self::TABLE;

    /** {{ '@' }}var bool */
    public $timestamps = {{ $modelDefinition->timestamps ? 'true' : 'false' }};

    /** {{ '@' }}var array|string[] */
    protected $fillable = [];

    /** {{ '@' }}var array|string[] */
    protected $guarded = [];

@if($modelDefinition->hasDates())
    /** {{ '@' }}var array|string[] */
    protected $dates = [
@foreach ($modelDefinition->getDates() as $column)
        '{{ $column }}',
@endforeach
    ];
@else
    /** {{ '@' }}var array|string[] */
    protected $dates = [];
@endif

    /** {{ '@' }}var array|string[] */
    protected $casts = [];

<?php /** @var \SimKlee\LaravelCraftingTable\Models\ColumnDefinition $columnDefinition */ ?>
@foreach($modelDefinition->getColumnsWithForeignKey() as $columnDefinition)
    public function {{ Str::camel($columnDefinition->foreignKeyModel) }}(): BelongsTo
    {
        return $this->belongsTo({{ $columnDefinition->foreignKeyModel }}::class);
    }

@endforeach
}
