<?php declare(strict_types=1);

namespace Vic\ProductInquiry\Core\Content\Inquiry;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\DateField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FloatField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\UpdatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

// EntityDefinition es el "mapa" entre la tabla SQL y la Entity PHP.
// Aquí definimos: nombre de tabla, qué clase PHP representa cada fila,
// y la lista de campos con su tipo y flags.
class InquiryDefinition extends EntityDefinition
{
    // Constante con el nombre de la tabla. La usaremos también en la migración
    // para evitar escribir el string en dos sitios distintos.
    public const ENTITY_NAME = 'vic_product_inquiry';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return InquiryEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            // IdField: BINARY(16) en SQL. Shopware usa UUIDs almacenados en binario
            // por rendimiento. El DAL convierte automáticamente entre string y binario.
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),

            // StringField(columna_sql, propiedad_php): VARCHAR(255) por defecto.
            new StringField('product_id', 'productId'),
            new StringField('product_name', 'productName'),
            (new StringField('customer_name', 'customerName'))->addFlags(new Required()),
            (new StringField('customer_email', 'customerEmail'))->addFlags(new Required()),

            new LongTextField('message', 'message'),

            // Campos de alquiler — todos opcionales, solo se rellenan si el producto
            // tiene el modo consulta activo y el cliente selecciona fechas.
            new DateField('start_date', 'startDate'),
            new DateField('end_date', 'endDate'),
            new IntField('rental_days', 'rentalDays'),
            new FloatField('total_price', 'totalPrice'),

            new CreatedAtField(),
            new UpdatedAtField(),
        ]);
    }
}
