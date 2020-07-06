<template>
    <v-card>
        <v-card-title >
            <span class="headline" >Сотрудники</span>
            <v-spacer></v-spacer>
            <v-row no-gutters>
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Поиск"
                        single-line
                        class="mr-7"
                    ></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="employmentDate"
                        label="Прием на работу"
                        single-line
                        type="date"
                        @change="loadWorkers"
                    ></v-text-field>
                </v-col>
            </v-row>

            <v-spacer></v-spacer>
            <v-btn
                color="primary"
                to="workers/create"
                class="buttonText--text"
            >
                <v-icon left>mdi-account-plus</v-icon>
                Добавить
            </v-btn>

        </v-card-title>
        <v-data-table
            :headers="headers"
            :items="workers"
            :loading="loading"
            class="elevation-1"
            :server-items-length="workersData.total"
            :options.sync="pagination"
            :footer-props="{'items-per-page-options': rowsPerPageItems}"
            must-sort
        >
            <template v-slot:item.boss_surname="{ item }">
                {{item.boss_surname?`${item.boss_surname} ${item.boss_name}`:''}}
            </template>
            <template v-slot:item.img="{ item }">
               <v-img class="my-1" width="75" :aspect-ratio="3/4" :src="imgUrl(item)"/>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-btn  :to="`/workers/${item.id}/edit`" fab outlined x-small class="mx-2" color="warning">
                    <v-icon small>mdi-pencil</v-icon>
                </v-btn>
                <v-btn @click="onDeleteWorker(item)" fab outlined x-small  class="mx-2" color="error">
                    <v-icon small>mdi-delete</v-icon>
                </v-btn>
            </template>
            <template v-slot:no-data>
                <v-btn color="primary" @click="initialize">Обновить</v-btn>
            </template>

        </v-data-table>
        <swap-form :item="swapWorker" :dialog="swapDialog" @close="swapDialog=false" />
    </v-card>
</template>
<script>
    import {mapActions} from "vuex"
    import SwapForm from "../components/SwapForm";
    export default {
        components: {SwapForm},
        data: () => ({
            headers: [
                { text: '  ', value: 'img', sortable:false, },
                { text: 'Фамилия', value: 'surname' },
                { text: 'Имя',value: 'name', },
                { text: 'Отчество', value: 'patronymic' },
                { text: 'Дата приёма на работу', value: 'employment_date' },
                { text: 'Зарплата(руб)', value: 'salary' },
                { text: 'Должность', value: 'position_name' },
                { text: 'Начальник(ца)', value: 'boss_surname' },
                {
                    text: '',
                    align: 'end',
                    sortable: false,
                    value: 'actions'
                },
            ],
            workersData: {
                data:[],
            },
            pagination:{
                page: 1,
                itemsPerPage: 10,
                sortBy: [],
                sortDesc: [],
                groupBy: [],
                groupDesc: [],
                multiSort: true,
                mustSort: false,
            },
            loading:false,
            workerFormDialog:false,
            editItem:{},

            page:1,
            limit:10,
            rowsPerPageItems: [5,10, 20, 30, 40, 50],

            search:'',
            employmentDate:'',

            swapDialog:false,
            swapWorker:{},
        }),
        computed:{
            workers(){
                return this.workersData.data;
            },
        },
        watch:{
            pagination:{
                handler () {
                    this.loadWorkers();
                },
                deep: true
            },
            search(){
                this.loadWorkers();
            }
        },
        mounted() {
            this.initialize()
        },
        methods:{
            ...mapActions(['fetchWorkers','deleteWorker']),
            initialize(){
                this.loadWorkers()
            },
            loadWorkers(){
                this.loading = true;

                let payload = {
                    params:{
                        page:this.pagination.page,
                        limit:this.pagination.itemsPerPage,
                        sort_by:this.pagination.sortBy,
                        sort_desc:this.pagination.sortDesc,
                        search:this.search,
                        employment_date:this.employmentDate,
                    }
                };

                this.fetchWorkers(payload)
                    .then(response => this.workersData = response.data.workers)
                    .catch(error => console.log(error))
                    .finally(() => this.loading = false);

            },
            deleteItem(item){
                const index = this.workersData.data.indexOf(item);
                this.workersData.data.splice(index,1);

                this.deleteWorker(item)
            },
            onDeleteWorker(item){
                confirm('Удалить данного сотрудника ?') && this.deleteItem(item);
            },
            paginate (val) {
                this.loadWorkers();
            },
            imgUrl(item){
                let index = item.img.search('https');
                return index === 0? item.img : '/storage/'+item.img;
            },

        }
    }
</script>

