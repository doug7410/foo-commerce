<template>
    <form @submit.prevent="save">
        <div class="row" v-if="errors">
            <div class="col" >
                <div class="alert alert-danger" role="alert">
                    <strong class="fs-5 me-3">Error</strong> {{ errors.message }}
                    <ul>
                        <li v-for="(messages, error) in errors.errors">
                            <span v-for="(message, index) in messages" :key="index" class="me-2">{{message}}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <label for="product_name" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="product_name" v-model="product.product_name">
            </div>
        </div>
        <div class="row mb-3">
            <label for="description" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="description" v-model="product.description"></textarea>
            </div>
        </div>
        <div class="row mb-3">
            <label for="style" class="col-sm-2 col-form-label">Style</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="style" v-model="product.style">
            </div>
        </div>
        <div class="row mb-3">
            <label for="brand" class="col-sm-2 col-form-label">Brand</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="brand" v-model="product.brand">
            </div>
        </div>
        <div class="row mb-3">
            <label for="product_type" class="col-sm-2 col-form-label">Type</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="product_type" v-model="product.product_type">
            </div>
        </div>
        <div class="row mb-3">
            <label for="shipping_price" class="col-sm-2 col-form-label">Shipping Price</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" id="shipping_price" v-model="product.shipping_price">
            </div>
        </div>
        <div class="row mb-3">
            <label for="note" class="col-sm-2 col-form-label">Note</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="note" v-model="product.note"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 ms-3 mt-3">
                <button type="submit" class="btn btn-primary me-3 w-50">Save</button>
                <button class="btn btn-light" @click.prevent="cancel">Cancel</button>
            </div>
        </div>

    </form>
</template>

<script>
    export default {
        name: 'ProductForm',

        props: {
          editProduct: {
              required: false
          }
        },

        data () {
            return {
                product: {
                    product_name: null,
                    description: null,
                    style: null,
                    brand: null,
                    product_type: null,
                    shipping_price: null,
                    note: null,
                },
                status: null,
                errors: null,
            }
        },

        methods: {
            cancel () {
                this.$emit('cancel')
            },

            save () {
                const url = this.editProduct ? `/api/products/${this.editProduct.id}` : '/api/products'
                window.axios.post(url, this.product).then(() => {
                    this.status = 'saved'
                    this.$emit('saved', this.product)
                    this.product =  {
                        product_name: null,
                        description: null,
                        style: null,
                        brand: null,
                        product_type: null,
                        shipping_price: null,
                        note: null,
                    }
                    this.editProduct = null
                }).catch(error => {
                    this.errors = error.response.data
                })
            }
        },

        created () {
            if(this.editProduct) {
                this.product = { ...this.product , ...this.editProduct }
            }
        }
    }
</script>

<style scoped>

</style>