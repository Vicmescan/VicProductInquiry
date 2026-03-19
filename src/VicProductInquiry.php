<?php declare(strict_types=1);

namespace Vic\ProductInquiry;

use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;
use Vic\ProductInquiry\Installer\CustomFieldInstaller;

class VicProductInquiry extends Plugin
{
    public function install(InstallContext $installContext): void
    {
        parent::install($installContext);

        $this->getCustomFieldInstaller()->install($installContext->getContext());
    }

    public function uninstall(UninstallContext $uninstallContext): void
    {
        parent::uninstall($uninstallContext);

        // keepUserData() devuelve true cuando el usuario elige "desinstalar pero conservar datos".
        // En ese caso respetamos su decisión y no borramos los custom fields ni sus valores.
        if ($uninstallContext->keepUserData()) {
            return;
        }

        $this->getCustomFieldInstaller()->uninstall($uninstallContext->getContext());
    }

    public function update(UpdateContext $updateContext): void
    {
        parent::update($updateContext);

        // Al actualizar el plugin también nos aseguramos de que los custom fields existen,
        // por si se instaló una versión anterior que no los tenía.
        $this->getCustomFieldInstaller()->install($updateContext->getContext());
    }

    private function getCustomFieldInstaller(): CustomFieldInstaller
    {
        // El container de Symfony tiene registrado el repositorio 'custom_field_set.repository'.
        // Todos los repositorios en Shopware siguen el patrón: '{nombre_entidad}.repository'.
        return new CustomFieldInstaller(
            $this->container->get('custom_field_set.repository')
        );
    }
}
