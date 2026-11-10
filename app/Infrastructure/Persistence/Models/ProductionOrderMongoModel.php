<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Models;

use MongoDB\Laravel\Eloquent\Model;

/**
 * @property string $_id
 * @property int $work_order_id
 * @property int $customer_id
 * @property string $status
 * @property int|null $mechanic_id
 * @property string|null $cancellation_reason
 * @property \MongoDB\BSON\UTCDateTime|null $received_at
 * @property \MongoDB\BSON\UTCDateTime|null $started_at
 * @property \MongoDB\BSON\UTCDateTime|null $finished_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
final class ProductionOrderMongoModel extends Model
{
    protected $connection = 'mongodb';
    
    protected $collection = 'production_orders';

    protected $primaryKey = '_id';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * Campos atribuíveis em massa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'work_order_id',
        'customer_id',
        'status',
        'mechanic_id',
        'cancellation_reason',
        'received_at',
        'started_at',
        'finished_at',
    ];

    /**
     * Conversões de tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'work_order_id' => 'integer',
        'customer_id' => 'integer',
        'status' => 'string',
        'mechanic_id' => 'integer',
        'cancellation_reason' => 'string',
    ];
}