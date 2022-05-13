<template>
    <div>
        <div class="row" v-if="notification">
            <div class="col">
                <div class="alert alert-success alert-dismissible" role="alert">
                    {{ notification }}
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
            <div class="d-flex justify-content-center" v-if="loading">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <table class="table table-striped table-sm table-hover" v-else>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Style</th>
                    <th>Brand</th>
                    <th>Sku's</th>
                    <th>
                        <a href="#" @click.prevent="sortByPotentialRevenue">Potential Revenue</a>
                        <span v-if="filters.sort.potential_revenue">
                            ({{ filters.sort.potential_revenue }})
                        </span>
                    </th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(product, index) in products" :key="index">
                    <td>
                        <a href="#" @click.prevent="edit(product)">{{ product.product_name }}</a>
                    </td>
                    <td>{{ product.style }}</td>
                    <td>{{ product.brand }}</td>
                    <td>
                        <a :href="`/inventory?sku=${item.sku}`"
                           v-for="(item, index) in product.inventory"
                           :key="index"
                           class="d-inline-block me-1"
                        >
                            {{ item.sku }}
                        </a>
                    </td>
                    <td>{{ product.potential_revenue | money }}</td>
                    <td>
                        <div>
                            <button type="button" class="btn btn-danger btn-sm" @click="openDeleteModal(product)">
                                delete
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>

        </div>
        <modal name="product-form" :shift-y="0.1" width="800" :adaptive="true" height="auto">
            <div class="container p-3">
                <h3>Create Product</h3>
                <hr>
                <product-form @cancel="hideProductForm"
                              @saved="(product) => productSaved(product)"
                              :edit-product="currentProduct"
                ></product-form>
            </div>
        </modal>

        <modal name="delete-product">

            <div class="container p-4 text-center">
                <div class="row">
                    <div class="col">
                        <h2>Are you sure?</h2>
                        <p v-if="deleting" class="me-3">Deleting "{{ deleting.product_name }}"</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col d-flex justify-content-center mt-5">
                    <button class="btn btn-danger me-3" @click="deleteProduct">Delete Product</button>
                    <button class="btn btn-light" @click="closeDeleteModal">Cancel</button>
                </div>
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
                currentProduct: null,
                deleting: null,
                loading: false,
                filters: {
                    sort: {}
                }
            }
        },

        mounted () {
            this.getProducts()
        },

        methods: {
            getProducts () {
                this.loading = true
                window.axios.get('/api/products', {
                    params: {
                        filters: this.filters
                    }
                }).then(res => {
                    this.products = res.data.products
                    this.loading = false
                })
            },
            createNewProduct () {
                this.$modal.show('product-form')
            },
            productSaved (product) {
                this.$modal.hide('product-form')
                this.getProducts()
                this.notification = `Product "${product.product_name}" Saved`
            },
            edit (product) {
                this.currentProduct = product
                this.$modal.show('product-form')
            },
            hideProductForm () {
                this.$modal.hide('product-form')
                this.currentProduct = null
            },
            openDeleteModal (product) {
                this.deleting = product
                this.$modal.show('delete-product')
            },
            closeDeleteModal () {
                this.deleting = null
                this.$modal.hide('delete-product')
            },
            deleteProduct () {
                window.axios.delete(`/api/products/${this.deleting.id}`).then(() => {
                    this.getProducts()
                    this.notification = `Product "${this.deleting.product_name}" Deleted`
                    this.deleting = null
                    this.$modal.hide('delete-product')
                })
            },
            sortByPotentialRevenue () {
                if (!this.filters.sort.potential_revenue) { // if we don't have the potential_revenue sort yet
                    this.filters.sort.potential_revenue = 'desc'
                    this.getProducts()
                    return
                }

                if (this.filters.sort.potential_revenue && this.filters.sort.potential_revenue === 'desc') {
                    this.filters.sort.potential_revenue = 'asc'
                    this.getProducts()
                    return
                }

                if (this.filters.sort.potential_revenue && this.filters.sort.potential_revenue === 'asc') {
                    this.filters.sort.potential_revenue = null
                    this.getProducts()
                }
            },
        },

        components: {
            ProductForm
        }
    }
</script>

<style scoped>

</style>