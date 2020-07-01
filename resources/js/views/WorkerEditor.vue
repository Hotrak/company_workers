<template>
    <v-form
        ref="form"
        v-model="formValid">
        <v-card :loading="loading">
            <v-card-title>
                <span>{{title}}</span>
                <v-spacer/>
                <v-btn  @click="showSwapDialog()" fab outlined x-small class="mx-2" color="primary">
                    <v-icon small>mdi-swap-horizontal</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text>

                <v-row>
                    <v-col cols="12" md="3">
                        <v-card  color="background_panel"  @click="onPickFile">
                            <v-img :aspect-ratio="3/4"  :src="imgUrl"  />
                            <input name="imgData" hidden type="file" ref="fileInput" accept="image/*" @change="onFilePicked"/>

                        </v-card>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-card  color="background_panel">
                            <v-card-text>
                                <v-col cols="12">
                                    <v-text-field v-model="editItem.name" :rules="nameRules" label="Имя" required></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field v-model="editItem.surname" :rules="nameRules" label="Фамилия" required></v-text-field>
                                </v-col>
                                <v-col cols="12" >
                                    <v-text-field v-model="editItem.patronymic" :rules="nameRules" label="Отчество" required></v-text-field>
                                </v-col>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-card  color="background_panel">
                            <v-card-text>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="editItem.employment_date"
                                        :rules="mustRules"
                                        label="Приём на работу"
                                        required
                                        type="date"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        :rules="salaryRules"
                                        v-model="editItem.salary"
                                        label="Зарплата"
                                        type="number"
                                        required>
                                    </v-text-field>
                                </v-col>
                                <v-col cols="12" >
                                    <v-select
                                        :items="positions"
                                        v-model="editItem.position_id"
                                        item-text="name"
                                        item-value="id"
                                        label="Должность"
                                        :rules="mustRules"
                                        :loading="positionsLoading"
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" >
                                    <v-autocomplete
                                        v-model="editItem.parent_id"
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
                                </v-col>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>

            </v-card-text>
            <v-card-actions>
                <v-spacer/>
                <v-btn color="primary" @click="onSubmit">Сохранить</v-btn>
            </v-card-actions>
        </v-card>
        <info-dialog
            :dialog="dialog.dialog"
            :title="dialog.title"
            :message="dialog.message"
            @close="dialog.dialog = false"
        />
        <swap-form
            v-if="isUpdate"
            :dialog="swapDialog"
            :item="swapWorker"
            @close="swapDialog = false"
        />
    </v-form>
</template>
<script>
    import {mapActions} from 'vuex'
    import InfoDialog from "../components/InfoDialog";
    import SwapForm from "../components/SwapForm";
    export default {
        components: {SwapForm, InfoDialog},
        data:()=>({
            nameRules: [
                v => !!v || 'Поле обязательно для заполнения',
                v => (v && v.length <= 10) || 'Имя должно быть меньше 10 символов',
                v => (!v || /\S/.test(v)) || 'Не коректные данные',
                v => (!v || v.split(' ').length === 1) || 'Не коректные данные',
            ],
            mustRules: [
                v => !!v || 'Поле обязательно для заполнения',
            ],
            salaryRules:[
                v => !!v || 'Поле обязательно для заполнения',
                v => (v && v < 100000) || 'Не коректная сумма',
                v => (v && v > 0) || 'Не коректная сумма',
            ],
            dialog:{
                dialog:false,
                title:'',
                message:'',
            },
            formValid:false,
            form:null,
            positions:[],
            workersData:[],
            positionsLoading:false,
            workersLoading:false,
            workersSearch:null,
            workersSelect:null,
            img:{},
            imageUrl:'',
            isNewImg:false,
            editItem:{},
            swapDialog:false,
            swapWorker:{},
            loading:false,
        }),
        mounted(){
            this.loadPositions();
            this.loadWorkers();

            if(this.$route.params.id){
                this.loadWorker(this.$route.params.id)
            }
        },
        watch:{
            editItem(){
                if(this.isUpdate){
                    this.workersSearch = this.editItem.boss_surname+" "+this.editItem.boss_name;
                    this.loadWorkers();
                }
            },
            workersSearch (val) {
                val && val !== this.editItem.parent_id && this.loadWorkers()
            },

        },

        methods:{
            ...mapActions(['storeWorker','updateWorker','fetchPositions','fetchWorkers',"fetchWorker"]),
            onSubmit(){
                this.$refs.form.validate();
                if(!this.formValid)
                    return;

                if(!this.isUpdate && !this.isNewImg)
                    return alert('Выберите изображение');

                let formData = new FormData();

                if(this.isNewImg){
                    formData.append('img_url', this.img);
                    console.log("NEW_IMG",this.img);
                }

                if(this.editItem.parent_id)
                    formData.append('parent_id', this.editItem.parent_id);

                formData.append('name', this.editItem.name);
                formData.append('surname', this.editItem.surname);
                formData.append('patronymic', this.editItem.patronymic);
                formData.append('position_id', this.editItem.position_id);
                formData.append('employment_date', this.editItem.employment_date);
                formData.append('salary', this.editItem.salary);

                this.isUpdate?this.update(formData):this.store(formData);
            },
            loadPositions(){
                this.positionsLoading = true;

                this.fetchPositions()
                    .then(response => this.positions = response.data.positions)
                    .catch(errors => console.log(errors))
                    .finally(() => this.positionsLoading=false)
            },
            loadWorkers(){
                this.workersLoading = true;
                console.log('SEARCH',this.workersSearch);
                let payload = {
                    params:{
                        limit:15,
                        search:this.workersSearch,
                    }
                };
                this.fetchWorkers(payload)
                    .then(response => this.workersData = response.data.workers.data)
                    .catch(errors => console.log(errors))
                    .finally(() => this.workersLoading = false)
            },
            loadWorker(id){
                this.loading = true;
                this.fetchWorker({id})
                    .then(response=>this.editItem = response.data.worker)
                    .catch(errors=>console.log(errors))
                    .finally(() => this.loading = false)
            },
            store(data){
                this.storeWorker(data)
                    .then(response => {
                        console.log(response.data);
                        this.showDialog('Добавление сотрудника','Сотрудник успешно добавлен.');
                        this.reset()
                    })
                    .catch(errors => console.log(errors))
            },
            update(data){
                let payload = {
                    id:this.editItem.id,
                    data:data,
                };
                this.updateWorker(payload)
                    .then(response => {
                        this.showDialog('Редактирование сотрудника','Данные о сотруднике успешно изменены.');
                        console.log(response.data);
                    })
                    .catch(errors => console.log(errors))
            },
            onPickFile(){
                this.$refs.fileInput.click();
            },
            onFilePicked(e){
                const files = e.target.files;
                if(!this.isFileImage(files[0])){
                    return alert('Пожалусто выберите корректное изоброжение');
                }

                const fileReader = new FileReader();
                fileReader.addEventListener('load',()=>{
                    this.editItem.img = fileReader.result;
                    this.imageUrl = fileReader.result;
                });
                fileReader.readAsDataURL(files[0]);
                this.isNewImg = true;

                this.img = files[0];
            },
            isFileImage(file) {
                return file && file['type'].split('/')[0] === 'image';
            },
            reset(){
                this.$refs.form.reset();
                this.isNewImg = false;
                this.editItem.img = '';
                this.imageUrl = '';
            },
            showDialog(title,message){
                this.dialog.title = title;
                this.dialog.message = message;
                this.dialog.dialog = true;
            },
            showSwapDialog(){
                this.swapDialog = true;
                this.swapWorker = this.editItem;
            }
        },
        computed:{
            title(){
                return this.isUpdate?'Редактирование сотрудника':'Добавление сотрудника'
            },
            isUpdate(){
                return this.editItem.id;

            },
            workers(){
                if(this.isUpdate)
                    this.workersData = this.workersData.filter(item=>item.id !== this.editItem.id);
                this.workersData.forEach(item=>{
                    item.label = `${item.surname} ${item.name}`
                });

                return this.workersData;
            },
            imgUrl:{
                get(){
                    if(this.isUpdate){
                        if(this.isNewImg)
                            return this.editItem.img;
                        else{
                            const index = this.editItem.img.search('https');
                            return index === 0? this.editItem.img : '/storage/'+this.editItem.img;
                        }
                    }
                    else{
                        if(this.imageUrl === '')
                            return 'https://748073e22e8db794416a-cc51ef6b37841580002827d4d94d19b6.ssl.cf3.rackcdn.com/not-found.png';
                        return this.imageUrl;
                    }
                },
                set(e){
                    this.editItem.img = e;
                }
            }
        }
    }
</script>
