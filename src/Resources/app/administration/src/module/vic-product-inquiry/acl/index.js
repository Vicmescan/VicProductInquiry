// Declara los privilegios disponibles para este módulo en la pantalla
// Configuración > Usuarios y permisos > Roles. Al marcar "Viewer" para
// "Vic Product Inquiry", Shopware concede vic_product_inquiry:read.
Shopware.Service('privileges').addPrivilegeMappingEntry({
    category: 'permissions',
    parent: 'catalogues',
    key: 'vic_product_inquiry',
    roles: {
        viewer: {
            privileges: [
                'vic_product_inquiry:read',
            ],
            dependencies: [],
        },
    },
});
