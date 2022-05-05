<template>
    <div>
        <div class="card">
            <div class="card-body">
                <div class="mb-2">
                    <span class="fs-4">{{paginatedInventory.total}} <small>Records Shown</small></span>
                </div>
                <form class="w-75" @submit.prevent="getResults">
                    <div class="row mb-3">
                        <div class="col col-4">
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
                        <div class="col">
                            <input type="number"
                                   class="form-control"
                                   placeholder="QTY threshold"
                                   aria-label="QTY threshold"
                                   v-model="filters.qtyThreshold"
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
        <hr>
        <Pagination :data="paginatedInventory" @pagination-change-page="getResults" :limit="5"/>
        <div class="item-list">
            <div class="d-flex justify-content-center" v-if="loading">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <table class="table table-striped table-sm table-hover" v-else>
                <tr>
                    <th>Product Name</th>
                    <th>sku</th>
                    <th>quantity</th>
                    <th>color</th>
                    <th>size</th>
                    <th>price</th>
                    <th>cost</th>
                </tr>
                <tr v-for="(item, index) in paginatedInventory.data" :key="index">
                    <td>{{ item.product.product_name }}</td>
                    <td>{{ item.sku }}</td>
                    <td>{{ item.quantity }}</td>
                    <td>{{ item.color }}</td>
                    <td>{{ item.size }}</td>
                    <td>{{ item.price_cents }}</td>
                    <td>{{ item.cost_cents }}</td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
    import LaravelVuePagination from 'laravel-vue-pagination'
    import Multiselect from 'vue-multiselect'

    export default {
        name: 'InventoryList',

        data () {
            return {
                paginatedInventory: {},
                filters: {
                    product: null,
                    sku: null,
                    qtyThreshold: null
                },
                products: [],
                loading: false,
            }
        },

        methods: {
            getResults (page = 1) {
                this.loading = true
                window.axios.get(`/api/inventory`, {
                    params: {
                        page: page,
                        filters: this.formattedFilters
                    }
                }).then(res => {
                    this.paginatedInventory = res.data
                    this.loading = false
                })
            },
            clearFilters () {
                this.filters = {
                    product: null,
                    sku: null,
                    qtyThreshold: null
                }

                this.getResults()
            },
        },

        computed: {
            formattedFilters () {
                const formatted = []

                if (this.filters.product) {
                    formatted.push(['product_id', '=', this.filters.product.id])
                }

                if (this.filters.sku) {
                    formatted.push(['sku', '=', this.filters.sku])
                }

                if (this.filters.qtyThreshold) {
                    formatted.push(['quantity', '<=', this.filters.qtyThreshold])
                }

                return JSON.stringify(formatted)
            }
        },

        mounted () {

            const params = new Proxy(new URLSearchParams(window.location.search), {
                get: (searchParams, prop) => searchParams.get(prop),
            });

            window.axios.get('/api/products').then(res => {
                this.products = res.data.products
                this.filters.sku = params.sku
                this.getResults()
            })
        },

        components: {
            'Pagination': LaravelVuePagination,
            Multiselect
        }
    }
</script>

<style scoped>

</style>