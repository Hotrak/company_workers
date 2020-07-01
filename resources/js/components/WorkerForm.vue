<template>
    <v-dialog v-model="dialog" persistent max-width="900">
        <v-form
            ref="form"
            v-model="formValid">
            <v-card>
                <v-card-title>
                    {{title}}
                </v-card-title>
                <v-card-text>

                    <v-row>
                        <v-col>
                            <v-card  color="background_panel">
                                <v-img :aspect-ratio="3/4"  :src="imgUrl"  @click="onPickFile"/>
                                <input name="imgData" hidden type="file" ref="fileInput" accept="image/*" @change="onFilePicked"/>

                            </v-card>
                        </v-col>
                        <v-col>
                            <v-card  color="background_panel">
                                <v-card-text>
                                    <v-col cols="12">
                                        <v-text-field v-model="editItem.name" :rules="nameRules" label="Имя" required></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field v-model="editItem.surname" :rules="mustRules" label="Фамилия" required></v-text-field>
                                    </v-col>
                                    <v-col cols="12" >
                                        <v-text-field v-model="editItem.patronymic" :rules="mustRules" label="Отчество"></v-text-field>
                                    </v-col>
                                </v-card-text>
                            </v-card>
                        </v-col>
                        <v-col>
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
                                            <!--                                            <template v-slot:item="data">-->
                                            <!--                                                {{`${data.label}`}}-->
                                            <!--                                            </template>-->
                                            <!--                                            <template v-slot:selection="data">-->
                                            <!--                                               {{`${data.label}`}}-->
                                            <!--                                            </template>-->
                                        </v-autocomplete>
                                    </v-col>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>

                </v-card-text>
                <v-card-actions>
                    <v-spacer/>
                    <v-btn color="warning" @click="close">Закрыть</v-btn>
                    <v-btn color="primary" @click="onSubmit">Сохранить</v-btn>
                </v-card-actions>
            </v-card>

        </v-form>
    </v-dialog>
</template>
<script>
    import {mapActions} from 'vuex'
    export default {
        props:['dialog','editItem'],
        data:()=>({
            nameRules: [
                v => !!v || 'Поле обязательно для заполнения',
                v => (v && v.length <= 10) || 'Имя должно быть меньше 10 символов',
            ],
            mustRules: [
                v => !!v || 'Поле обязательно для заполнения',
                v => (v && /\S/.test(v)) || 'Не коректные данные',
            ],
            salaryRules:[
                v => !!v || 'Поле обязательно для заполнения',
                v => (v && v < 100000) || 'Не коректная сумма',
                v => (v && v > 0) || 'Не коректная сумма',
            ],
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
        }),
        mounted(){
            this.loadPositions();
            this.loadWorkers();
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
            ...mapActions(['storeWorker','updateWorker','fetchPositions','fetchWorkers']),
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

                if(this.isUpdate){
                    this.update(formData)
                }else
                    this.store(formData);

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
                        limit:20,
                        search:this.workersSearch,
                    }
                };

                this.fetchWorkers(payload)
                    .then(response => {
                        console.log("WORKERS",response.data.workers.data);
                        this.workersData = response.data.workers.data
                    })
                    .catch(errors => console.log(errors))
                    .finally(() => this.workersLoading = false)
            },
            store(data){
                this.storeWorker(data)
                    .then(response => {
                        console.log(response.data);
                        this.close();
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
                        console.log(response.data);
                        this.$emit('update',response.data.worker);
                        this.close();
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
            close(){
                this.$refs.form.reset();
                this.isNewImg = false;
                this.$emit('close');
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
                    this.workersData = this.workersData.filter(item => item.id !== this.editItem.id);

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
