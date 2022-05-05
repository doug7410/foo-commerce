<template>
    <div>
        <div class="card">
            <div class="card-body">
                <div class="mb-2">
                    <span class="fs-4">{{paginatedInventory.total}} <small>Records Shown</small></span>
                </div>
                <form class="w-75" @submit.prevent="getResults">
                    <div class="row mb-3">
                        <div class="col">
                            <select class="form-select"
                                    aria-label="Product"
                                    v-model="filters.product"
                            >
                                <option v-for="(product, index) in products"
                                        :key="index"
                                        :value="product.id"
                                >
                                    {{ product.product_name }}
                                </option>
                            </select>
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
            <table class="table">
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
                products: []
            }
        },

        methods: {
            getResults (page = 1) {
                window.axios.get(`/api/inventory`, {
                    params: {
                        page: page,
                        filters: this.formattedFilters
                    }
                }).then(res => {
                    this.paginatedInventory = res.data
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
                    formatted.push(['product_id', '=', this.filters.product])
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
            this.getResults()
            window.axios.get('/api/products').then(res => {
                this.products = res.data.products
            })
        },

        components: {
            'Pagination': LaravelVuePagination
        }
    }
</script>

<style scoped>

</style>