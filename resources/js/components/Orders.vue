<template>
    <div>
        <div class="card">
            <div class="card-body">
                <div class="mb-2">
                    <span class="fs-4">{{paginatedOrders.total}} <small>Records Shown</small></span>
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
                        <td>{{ order.inventory.color }}</td>
                        <td>{{ order.inventory.size }}</td>
                        <td>{{ order.order_status }}</td>
                        <td>{{ order.total_cents }}</td>
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

    export default {
        name: 'orders',

        data () {
            return {
                paginatedOrders: {},
                filters: {

                },
                orders: [],
                loading: false,
            }
        },

        methods: {
            getResults (page = 1) {
                this.loading = true
                window.axios.get(`/api/orders`, {
                    params: {
                        page: page,
                    }
                }).then(res => {
                    this.paginatedOrders = res.data
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

        mounted () {
            this.getResults()
        },

        components: {
            'Pagination': LaravelVuePagination,
        }
    }
</script>

<style scoped>

</style>