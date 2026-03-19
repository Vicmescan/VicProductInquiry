<?php declare(strict_types=1);

namespace Vic\ProductInquiry\Installer;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\CustomField\CustomFieldTypes;

class CustomFieldInstaller
{
    // Nombre del set de custom fields. Usamos prefijo del plugin para evitar colisiones.
    public const CUSTOM_FIELD_SET_NAME = 'vic_product_inquiry';

    // Nombre del campo booleano que activará el modo consulta en un producto.
    public const FIELD_INQUIRY_ACTIVE = 'vic_product_inquiry_active';

    public function __construct(
        private readonly EntityRepository $customFieldSetRepository
    ) {
    }

    public function install(Context $context): void
    {
        // Comprobamos si el set ya existe para no duplicarlo si se reinstala el plugin.
        if ($this->customFieldSetExists($context)) {
            return;
        }

        $this->customFieldSetRepository->create([
            [
                // Nombre interno único del set.
                'name' => self::CUSTOM_FIELD_SET_NAME,

                // Etiquetas visibles en la administración de Shopware.
                'config' => [
                    'label' => [
                        'en-GB' => 'Product Inquiry',
                        'es-ES' => 'Consulta de producto',
                        'de-DE' => 'Produktanfrage',
                    ],
                ],

                // Vinculamos este set a la entidad "product".
                // Shopware mostrará estos campos en la ficha de cada producto.
                'relations' => [
                    ['entityName' => 'product'],
                ],

                // Los campos que contiene este set.
                'customFields' => [
                    [
                        'name' => self::FIELD_INQUIRY_ACTIVE,
                        'type' => CustomFieldTypes::BOOL,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Enable inquiry mode (hide cart button)',
                                'es-ES' => 'Activar modo consulta (ocultar botón carrito)',
                                'de-DE' => 'Anfragemodus aktivieren (Warenkorb-Button ausblenden)',
                            ],
                            // Posición del campo dentro del set (por si hay varios).
                            'customFieldPosition' => 1,
                        ],
                    ],
                ],
            ],
        ], $context);
    }

    public function uninstall(Context $context): void
    {
        // Buscamos el set por nombre para obtener su ID.
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', self::CUSTOM_FIELD_SET_NAME));

        $result = $this->customFieldSetRepository->searchIds($criteria, $context);

        if ($result->getTotal() === 0) {
            return;
        }

        // Borramos el set. Shopware borrará en cascada los campos que contiene.
        $ids = array_map(fn(string $id) => ['id' => $id], $result->getIds());
        $this->customFieldSetRepository->delete($ids, $context);
    }

    private function customFieldSetExists(Context $context): bool
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', self::CUSTOM_FIELD_SET_NAME));

        return $this->customFieldSetRepository->searchIds($criteria, $context)->getTotal() > 0;
    }
}