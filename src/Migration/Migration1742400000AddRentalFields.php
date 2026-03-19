<?php declare(strict_types=1);

namespace Vic\ProductInquiry\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1742400000AddRentalFields extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1742400000;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            ALTER TABLE `vic_product_inquiry`
                ADD COLUMN `start_date`   DATE           NULL AFTER `message`,
                ADD COLUMN `end_date`     DATE           NULL AFTER `start_date`,
                ADD COLUMN `rental_days`  INT UNSIGNED   NULL AFTER `end_date`,
                ADD COLUMN `total_price`  DECIMAL(10,2)  NULL AFTER `rental_days`
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
