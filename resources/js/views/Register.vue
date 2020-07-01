<template>
    <v-container
        fluid
    >
        <v-row
            align="center"
            justify="center"
        >
            <v-col
                cols="12"
                sm="8"
                md="5"
            >
                <v-card
                    class="elevation-12"
                    :loading="loading">
                    <v-toolbar
                        color="primary"
                        dark
                        flat
                    >
                        <v-toolbar-title class="buttonText--text">Регистрация</v-toolbar-title>
                        <v-spacer />
                    </v-toolbar>
                    <v-card-text>
                        <error-alert :errors="errors"/>
<!--                        <v-alert type="error" v-for="(item,index) in errors" :key="index">-->
<!--                            {{item}}-->
<!--                        </v-alert>-->
                        <v-form
                            ref="form"
                            v-model="valid">

                            <v-text-field
                                label="E-mail"
                                name="email"
                                prepend-icon="mdi-email"
                                type="email"
                                :rules="emailRules"
                                v-model="user.email"
                            />
                            <v-text-field
                                label="Имя"
                                name="name"
                                prepend-icon="mdi-account"
                                type="text"
                                :rules="nameRules"
                                v-model="user.name"
                            />
                            <v-text-field
                                v-on:keyup.enter="onSubmit"
                                id="password"
                                label="Пароль"
                                name="password"
                                prepend-icon="mdi-lock"
                                v-model="user.password"
                                :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="showPassword ? 'text' : 'password'"
                                @click:append="showPassword = !showPassword"
                                :rules="passwordRules"
                            />
                            <v-text-field
                                v-on:keyup.enter="onSubmit"
                                id="password"
                                label="Пароль"
                                name="password"
                                prepend-icon="mdi-lock"
                                type="password"
                                v-model="user.password_confirmation"
                                :rules="passwordConfirmationRules"
                            />
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn  to="login" text>Войти</v-btn>
                        <v-spacer />
                        <v-btn color="primary" class="buttonText--text" :loading="loading" @click.prevent="onSubmit">Зарегистрироваться</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>

</template>
<script>
    import {mapActions} from 'vuex'
    import ErrorAlert from "../components/ErrorAlert";

    export default {
        components: {ErrorAlert},
        data(){
            return {
                valid:false,
                showPassword:false,
                loading:false,
                errors:[],
                user:{
                    email:'',
                    name:'',
                    password:'',
                    password_confirmation:'',
                },
                emailRules: [
                    v => !!v || 'Поле E-mail обязательно для заполнения',
                    v => !v || /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'Некорректный e-mail адрес'
                ],
                nameRules: [
                    v => !!v || 'Поле Имя обязательно для заполнения',
                    v => (v && v.length <= 10) || 'Имя должно быть меньше 10 символов',
                ],
                passwordRules: [
                    v => !!v || 'Поле Пароль обязательно для заполнения',
                    v => (v && v.length >= 6) || 'Пароль должен быть больше 6 символов',
                ],
                passwordConfirmationRules: [
                    v => (!!v && v) === this.user.password||'Пароли не совполают'
                ],
            }
        },
        methods:{
            ...mapActions(['register']),
            onSubmit(){
                this.$refs.form.validate();
                if(this.valid){
                    this.registerUser();
                }
            },
            registerUser(){
                this.loading = true;
                this.register(this.user)
                    .then(response=>{
                        console.log(response.data);
                        this.$store.commit('setToken',response.data.token);
                        this.$store.commit('setUser',response.data.user);
                        this.$router.push('/');})
                    .catch(error=>{
                        console.log(error);
                        if(error.response.status === 422){
                            let errors = Object.values(error.response.data.errors);
                            errors = errors.flat();
                            console.log(errors);
                            this.errors = errors;
                        }})
                    .finally(() => this.loading = false)
            }
        }
    }
</script>
