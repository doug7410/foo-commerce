<template>
    <div>
        <div class="row" v-if="notification">
            <div class="col" >
                <div class="alert alert-success alert-dismissible" role="alert">
                    <strong class="fs-5 me-3">Success!</strong> {{ notification }}
                    <button type="button" class="btn-close" aria-label="Close" @click="notification = null"></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col col-4">
                <button class="btn btn-primary btn-sm" @click="createNewProduct">
                    + New Product
                </button>
            </div>
        </div>
        <hr>
        <div class="item-list">
            <table class="table">
                <tr>
                    <th>Name</th>
                    <th>Style</th>
                    <th>Brand</th>
                    <th>Sku's</th>
                </tr>
                <tr v-for="(product, index) in products" :key="index">
                    <td>{{ product.product_name }}</td>
                    <td>{{ product.style }}</td>
                    <td>{{ product.brand }}</td>
                    <td>
                        <a :href="`/inventory?sku=${item.sku}`" v-for="(item, index) in product.inventory" :key="index">
                            {{ item.sku }}
                        </a>
                    </td>
                </tr>
            </table>
        </div>
        <modal name="product-form" :shift-y="0.1" width="800" :adaptive="true" height="auto">
            <div class="container p-3">
                <h3>Create Product</h3>
                <hr>
                <product-form @cancel="$modal.hide('product-form')" @saved="(product) => productSaved(product)"></product-form>
            </div>

        </modal>
    </div>
</template>

<script>
    import ProductForm from './ProductForm'

    export default {
        name: 'ProductList',

        data () {
            return {
                products: [],
                creating: false,
                notification: null,
            }
        },

        mounted () {
            this.getProducts()
        },

        methods: {
            getProducts () {
                window.axios.get('/api/products').then(res => {
                    this.products = res.data.products
                })
            },
            createNewProduct () {
                this.$modal.show('product-form')
            },
            productSaved (product) {
                this.$modal.hide('product-form')
                this.getProducts()
                this.notification = `Product "${product.product_name}" Saved`
            }
        },

        components: {
            ProductForm
        }
    }
</script>

<style scoped>

</style>