<template>
    <v-dialog v-model="dialog" max-width="500" persistent>
        <v-card :loading="loading">
            <v-card-title>Замена {{`${item.surname} ${item.name}`}} </v-card-title>
            <v-card-text>
                <v-form v-model="valid" ref="form">
                    <v-autocomplete
                        v-model="newBossId"
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
                        :rules="bossRules"
                    >
                    </v-autocomplete>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="error" outlined  @click="close">Закрыть</v-btn>
                <v-btn @click.prevent="onSubmit" color="primary " outlined>Сохранить</v-btn>
            </v-card-actions>
        </v-card>
        <info-dialog
            :title="infoDialog.title"
            :message="infoDialog.message"
            :dialog="infoDialog.dialog"
            @close="infoDialog.dialog = false"
        />
    </v-dialog>
</template>
<script>
    import {mapActions} from 'vuex'
    import InfoDialog from "./InfoDialog";
    export default {
        components: {InfoDialog},
        props:['dialog','item'],
        data:(vm)=>({
            newBossId:-1,
            workersLoading:false,
            workersSearch:null,
            workersData:[],
            limit:20,
            loading:false,
            valid:false,
            bossRules:[
                v => !!v || 'Поле обязательно для заполнения',
                v => (v && v !== -1) || 'Поле обязательно для заполнения',
            ],
            infoDialog:{
                dialog:false,
                title:'',
                message:'',
            },
        }),
        mounted(){
            this.loadWorkers();
        },
        methods:{
            ...mapActions(['fetchWorkers','updateWorkerBoss']),
            loadWorkers(){
                this.workersLoading = true;
                let payload = {
                    params:{
                        limit:20,
                        search:this.workersSearch,
                    }
                };
                this.fetchWorkers(payload)
                    .then(response => this.workersData = response.data.workers.data)
                    .catch(errors => console.log(errors))
                    .finally(() => this.workersLoading = false)
            },
            onSubmit(){
                this.$refs.form.validate();
                if(this.valid)
                    this.updateBoss();
            },
            updateBoss(){
                this.loading = true;
                const payload = {
                    old_boss_id:this.item.id,
                    boss_id:this.newBossId,
                };
                this.updateWorkerBoss(payload)
                    .then(response=>{
                        this.showDialog("Замена начальника",'Замена успешно произведена.');
                        this.close();
                    })
                    .catch(errors=>console.log(errors))
                    .finally(() => this.loading = false)
            },
            close(){
                this.$emit('close');
            },
            showDialog(title,message){
                this.infoDialog.title = title;
                this.infoDialog.message = message;
                this.infoDialog.dialog = true;
            },

        },
        computed:{
            workers(){
                this.workersData = this.workersData.filter(item=>item.id !== this.item.id);
                this.workersData.forEach(item=>{
                    item.label = `${item.surname} ${item.name}`
                });

                return this.workersData;
            }
        },
        watch:{
            workersSearch (val) {
                val && val !== this.newBossId && this.loadWorkers()
            },
        }
    }
</script>
