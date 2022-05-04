<template>
    <div>
        <div class="card">
            Total Records: {{laravelData.total}}

            <select name="" id="" @change="filterByProduct" v-model="productFilter">
                <option value="">filter by product</option>
                <option value="87">Girl next door Kilt</option>
                <option value="115">Flamboyant Belt</option>
            </select>

            <label for="">SKU filter</label>
            <input type="text" v-model="skuFilter">
            <button class="btn" @click="filterBySku">filter by sky</button>

            <label for="">inventory filter</label>
            <input type="text" v-model="inventoryFilter">
            <button class="btn" @click="filterByInventory">filter by inventory</button>
        </div>
        <hr>
        <Pagination :data="laravelData" @pagination-change-page="getResults" :limit="5"/>
        <div class="item-list">
            <table class="table">
                <tr>
                    <th></th>
                    <th>Product Name</th>
                    <th>sku</th>
                    <th>quantity</th>
                    <th>color</th>
                    <th>size</th>
                    <th>price</th>
                    <th>cost</th>
                </tr>
                <tr v-for="(item, index) in laravelData.data" :key="index">
                    <td>{{ index+1 }}</td>
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
                items: [],
                laravelData: null,
                filters: [],
                productFilter: null,
                skuFilter: null,
                inventoryFilter: null,
            }
        },

        methods: {
            getResults (page = 1) {
                const filters = JSON.stringify(this.filters)
                window.axios.get(`/api/inventory`, {
                    params: {
                        page: page,
                        filters: filters
                    }
                }).then(res => {
                    this.laravelData = res.data
                })
            },
            filterByProduct () {
                if (this.productFilter) {
                    this.filters = [['product_id', '=', this.productFilter]]
                } else {
                    this.filters = []
                }

                this.getResults()
            },
            filterBySku () {
                if(this.skuFilter) {
                    this.filters = [['sku', '=', this.skuFilter]]
                } else {
                    this.filters = []
                }

                this.getResults()
            },
            filterByInventory(){
                if(this.inventoryFilter) {
                    this.filters = [['quantity', '<=', this.inventoryFilter]]
                } else {
                    this.filters = []
                }

                this.getResults()
            }
        },

        mounted () {
            this.getResults()
        },

        components: {
            'Pagination': LaravelVuePagination
        }
    }
</script>

<style scoped>

</style>