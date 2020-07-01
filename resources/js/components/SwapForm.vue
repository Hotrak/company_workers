<template>
    <v-dialog v-model="dialog" max-width="500" persistent>
        <v-card>
            <v-card-title>Замена начальника </v-card-title>
            <v-card-text>
                <v-autocomplete
                    v-model="newPossId"
                    :loading="workersLoading"
                    :items="workers"
                    item-text="label"
                    item-value="id"
                    :search-input.sync="workersSearch"
                    cache-items
                    flat
                    hide-no-data
                    hide-details
                    label="Начальник"
                >
                </v-autocomplete>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <!--                <v-btn color="primary " outlined  @click="close">Нет</v-btn>-->
                <v-btn @click.prevent="close" color="primary " outlined>Сохранить</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
    import {mapActions} from 'vuex'
    export default {
        props:['dialog','item'],
        data:()=>({
            newPossId:-1,
            workersLoading:false,
            workersSearch:null,
            workers:[],
            limit:20,
        }),
        methods:{
            ...mapActions(['fetchWorkers']),
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
           }
        }
    }
</script>
