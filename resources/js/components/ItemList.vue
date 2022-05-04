<template>
    <div>
        <h4>This is an item list for {{moduleName}}</h4>
        <div class="item-list">
            <table class="table">
                <tr>
                    <th></th>
                    <th v-for="(column, header) in columns" :key="header">{{ header }}</th>
                </tr>
                <tr v-for="(item, index) in items" :key="index">
                    <td>{{index+1}}</td>
                    <td v-for="(column, header) in columns" :key="header">{{ item[column] }}</td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ItemList',

        data () {
            return {
                items: [],
                columns: []
            }
        },

        props: {
            moduleName: {
                type: String,
                required: true
            }
        },

        created () {
            window.axios.get(`/api/module/${this.moduleName}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                }
            ).then(res => {
                this.items = res.data.items
                this.columns = res.data.columns
            })
        }
    }
</script>

<style scoped>

</style>