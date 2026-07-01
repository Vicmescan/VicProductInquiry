import template from './vic-product-inquiry-list.html.twig';

const { Criteria } = Shopware.Data;

// Component.register() define un componente Vue reutilizable.
// El nombre en kebab-case ('vic-product-inquiry-list') es como se referencia
// desde el módulo y desde otros templates.
Shopware.Component.register('vic-product-inquiry-list', {
    template,

    // inject permite acceder a servicios de Shopware. repositoryFactory es
    // el servicio que crea repositorios para cualquier entidad — aquí es donde
    // entra la "magia" de la API automática que mencionamos antes.
    inject: ['repositoryFactory'],

    data() {
        return {
            inquiries: null,
            isLoading: true,
            total: 0,
        };
    },

    computed: {
        // Creamos el repositorio para nuestra entidad. Internamente llama a /_api/vic-product-inquiry.
        inquiryRepository() {
            return this.repositoryFactory.create('vic_product_inquiry');
        },

        // Shopware.Filter.register('date', ...) expone el filtro como una función
        // inyectable, no como un filtro Twig "| date(...)" — así lo consumen los
        // módulos core (p.ej. sw-order-delivery-metadata).
        dateFilter() {
            return Shopware.Filter.getByName('date');
        },

        // Definición de columnas para el componente sw-data-grid de Shopware.
        columns() {
            return [
                { property: 'createdAt',     label: this.$tc('vic-product-inquiry.list.columnDate'),    sortable: true  },
                { property: 'productName',   label: this.$tc('vic-product-inquiry.list.columnProduct'),   sortable: true  },
                { property: 'customerName',  label: this.$tc('vic-product-inquiry.list.columnName'),      sortable: true  },
                { property: 'customerEmail', label: this.$tc('vic-product-inquiry.list.columnEmail'),     sortable: true  },
                { property: 'startDate',     label: this.$tc('vic-product-inquiry.list.columnStartDate'), sortable: true  },
                { property: 'endDate',       label: this.$tc('vic-product-inquiry.list.columnEndDate'),   sortable: true  },
                { property: 'message',       label: this.$tc('vic-product-inquiry.list.columnMessage'),   sortable: false },
            ];
        },
    },

    created() {
        this.loadInquiries();
    },

    methods: {
        loadInquiries() {
            const criteria = new Criteria();
            // Ordenamos por fecha descendente: las consultas más recientes primero.
            criteria.addSorting(Criteria.sort('createdAt', 'DESC'));

            // search() hace el GET a la API y devuelve una promesa.
            this.inquiryRepository.search(criteria, Shopware.Context.api).then((result) => {
                this.inquiries = result;
                this.total = result.total;
                this.isLoading = false;
            });
        },
    },
});
