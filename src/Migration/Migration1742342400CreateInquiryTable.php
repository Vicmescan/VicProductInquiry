<?php declare(strict_types=1);

namespace Vic\ProductInquiry\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

// Las migraciones en Shopware siguen el mismo concepto que en Laravel o Doctrine:
// un fichero por cambio de esquema, ordenados por timestamp.
// El nombre de la clase DEBE empezar por "Migration" seguido del timestamp.
// Shopware ejecuta automáticamente las migraciones pendientes al actualizar el plugin.
class Migration1742342400CreateInquiryTable extends MigrationStep
{
    // Este timestamp es el identificador único de la migración.
    // Shopware lo guarda en la tabla `migration` para saber cuáles ya se ejecutaron.
    public function getCreationTimestamp(): int
    {
        return 1742342400;
    }

    // update() contiene los cambios a aplicar (crear tablas, añadir columnas...).
    // Se ejecuta solo una vez. IF NOT EXISTS evita errores si se llama dos veces.
    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE IF NOT EXISTS `vic_product_inquiry` (
                `id`             BINARY(16)   NOT NULL,
                `product_id`     VARCHAR(255) NULL,
                `product_name`   VARCHAR(255) NULL,
                `customer_name`  VARCHAR(255) NOT NULL,
                `customer_email` VARCHAR(255) NOT NULL,
                `message`        LONGTEXT     NULL,
                `created_at`     DATETIME(3)  NOT NULL,
                `updated_at`     DATETIME(3)  NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    // updateDestructive() es para cambios irreversibles (DROP, TRUNCATE...).
    // Shopware lo separa de update() para que el administrador pueda decidir
    // cuándo ejecutar acciones destructivas. Lo dejamos vacío por ahora.
    public function updateDestructive(Connection $connection): void
    {
    }
}
