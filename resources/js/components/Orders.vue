<template>
    <div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col col-3">
                        <div class="mb-2">
                            <table class="table table-striped table-sm table-bordered">
                                <tbody>
                                <tr>
                                    <th>Records Shown</th>
                                    <td>{{paginatedOrders.total | number }}</td>
                                </tr>
                                <tr>
                                    <th>Total Sales</th>
                                    <td>{{ totalSales | money }}</td>
                                </tr>
                                <tr>
                                    <th>Average Sale</th>
                                    <td>{{ averageSale | money }}</td>
                                </tr>
                                <tr v-if="filteredTotalSales">
                                    <th>Filtered Total Sales</th>
                                    <td>{{ filteredTotalSales | money }}</td>
                                </tr>
                                <tr v-if="filteredAverageSale">
                                    <th>Filtered Average Sales</th>
                                    <td>{{ filteredAverageSale | money }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col">
                        <form @submit.prevent="getResults">
                            <div class="row mb-3">
                                <div class="col">
                                    <multiselect v-model="filters.product"
                                                 :options="products"
                                                 :custom-label="(product) => product.product_name"
                                                 placeholder="Product"
                                                 label="product_name"
                                                 track-by="id"
                                    ></multiselect>
                                </div>
                                <div class="col">
                                    <input type="text"
                                           class="form-control"
                                           placeholder="SKU"
                                           aria-label="SKU"
                                           v-model="filters.sku"
                                    >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-4">
                                    <button type="submit" class="btn btn-primary me-2">
                                        Apply Filters
                                    </button>
                                    <button class="btn btn-light" @click="clearFilters">
                                        Clear Filters
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <Pagination :data="paginatedOrders" @pagination-change-page="getResults" :limit="5"/>
        <div class="item-list">
            <div class="d-flex justify-content-center" v-if="loading">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <table class="table table-striped table-sm table-hover" v-else>
                <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Email Address</th>
                    <th>Product Name</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Order Status</th>
                    <th>Order Total</th>
                    <th>Transaction ID</th>
                    <th>Shipper</th>
                    <th>Tracking Number</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(order, index) in paginatedOrders.data" :key="index">
                    <td>{{ order.name }}</td>
                    <td>{{ order.email }}</td>
                    <td>{{ order.product.product_name }}</td>
                    <td>
                        <a :href="`/inventory?sku=${order.inventory.sku}`">
                            {{ order.inventory.color }}
                        </a>
                    </td>
                    <td>
                        <a :href="`/inventory?sku=${order.inventory.sku}`">
                            {{ order.inventory.size }}
                        </a>
                    </td>
                    <td>{{ order.order_status }}</td>
                    <td>{{ order.total_cents | money }}</td>
                    <td>{{ order.transaction_id }}</td>
                    <td>{{ order.shipper_name }}</td>
                    <td>{{ order.tracking_number }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import LaravelVuePagination from 'laravel-vue-pagination'
    import Multiselect from 'vue-multiselect'

    export default {
        name: 'orders',

        data () {
            return {
                paginatedOrders: {},
                orders: [],
                loading: false,
                totalSales: null,
                filteredTotalSales: null,
                filteredAverageSale: null,
                averageSale: null,
                products: [],
                filters: {
                    product: null,
                    sku: null,
                },
            }
        },

        methods: {
            getResults (page = 1) {
                this.loading = true
                window.axios.get(`/api/orders`, {
                    params: {
                        page: page,
                        filters: this.formattedFilters,
                    }
                }).then(res => {
                    this.paginatedOrders = res.data.paginated_orders
                    this.filteredTotalSales = res.data.filtered_total_sales
                    this.filteredAverageSale = res.data.filtered_average_sale
                    this.loading = false
                })
            },
            clearFilters () {
                this.filters = {
                    product: null,
                    sku: null,
                }

                this.getResults()
            },
        },

        mounted () {
            window.axios.get('/api/products?with_trashed=true').then(res => {
                this.products = res.data.products
            })

            window.axios.get('/api/orders/sales-report').then(res => {
                this.totalSales = res.data.total_sales
                this.averageSale = res.data.average_sale
            })

            this.getResults()
        },

        computed: {
            formattedFilters () {
                const formatted = {}

                if (this.filters.product) {
                    formatted.product_id =  this.filters.product.id
                }

                if (this.filters.sku) {
                    formatted.sku = this.filters.sku
                }

                return JSON.stringify(formatted)
            }
        },

        components: {
            'Pagination': LaravelVuePagination,
            Multiselect
        },
    }
</script>

<style scoped>

</style>