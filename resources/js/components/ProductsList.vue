<template>
    <div>
        <div class="item-list">
            <table class="table">
                <tr>
                    <th>Name</th>
                    <th>Style</th>
                    <th>Brand</th>
                </tr>
                <tr v-for="(item, index) in products" :key="index">
                    <td>{{ item.product_name }}</td>
                    <td>{{ item.style }}</td>
                    <td>{{ item.brand }}</td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ProductList',

        data () {
            return {
                products: [],
            }
        },

        props: {
            moduleName: {
                type: String,
                required: true
            }
        },

        created () {
            window.axios.get('/api/products').then(res => {
                this.products = res.data.products
            })
        }
    }
</script>

<style scoped>

</style>