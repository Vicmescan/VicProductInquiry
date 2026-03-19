import './page/vic-product-inquiry-list';
import enGB from './snippet/en-GB.json';

// Shopware.Module.register() es el equivalente a registrar una ruta en Vue Router,
// pero integrado en el sistema de módulos de Shopware.
// Cada módulo puede tener su propio menú, rutas e icono.
Shopware.Module.register('vic-product-inquiry', {
    type: 'plugin',
    name: 'vic-product-inquiry.general.title',
    title: 'vic-product-inquiry.general.title',
    color: '#ff68b4',
    icon: 'regular-envelope',

    snippets: {
        'en-GB': enGB,
    },

    routes: {
        list: {
            component: 'vic-product-inquiry-list',
            path: 'list',
        },
    },

    // navigation define el ítem que aparece en el menú lateral del admin.
    // parent: 'sw-catalogue' lo coloca bajo el menú "Catálogo".
    navigation: [{
        label: 'vic-product-inquiry.general.title',
        color: '#ff68b4',
        path: 'vic.product.inquiry.list',
        icon: 'regular-envelope',
        parent: 'sw-catalogue',
        position: 100,
    }],
});
