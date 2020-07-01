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
                <v-card class="elevation-12">
                    <v-toolbar
                        color="primary"
                        dark
                        flat
                        :loading="loading"
                    >
                        <v-toolbar-title class="buttonText--text">Вход</v-toolbar-title>

                    </v-toolbar>
                    <v-card-text>
                        <error-alert :errors="errors"/>

                        <v-spacer />
                        <v-form lazy-validation>
                            <v-text-field
                                label="E-mail"
                                name="login"
                                prepend-icon="mdi-email"
                                type="email"
                                v-model="user.email"
                                :rules="emailRules"
                            />
                            <v-text-field
                                v-on:keyup.enter="onSubmit"
                                id="password"
                                label="Пароль"
                                name="password"
                                prepend-icon="mdi-lock"
                                @click:append="showPassword = !showPassword"
                                :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"

                                :type="showPassword ? 'text' : 'password'"
                                v-model="user.password"

                            />


                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn  to="register" text >Зарегистрироваться</v-btn>
                        <v-spacer />
                        <v-btn color="primary" class="buttonText--text" :loading="loading" @click.prevent="onSubmit">Войти</v-btn>
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
        name:'login',
        components:{
            ErrorAlert,
        },
        data:()=>({
            user:{
                email:'',
                password:'',
            },
            showPassword: false,
            loading:false,
            errors:[],
            emailRules: [
                v => !!v || 'Поле E-mail обязательно для заполнения',
                v => !v || /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'Некорректный e-mail адрес'
            ],
        }),
        methods: {
            ...mapActions(['login']),
            onSubmit(){
                this.loginUser();
            },
            loginUser(){
                this.loading = true;

                this.errors = [];
                this.login(this.user).then(response=>{
                    console.log(response.data);
                    this.$store.commit('setToken',response.data.token);
                    this.$store.commit('setUser',response.data.user);
                    this.$router.push('/');
                }).catch(error=>{
                    console.log(error);
                    if(error.response.status === 422){
                        this.errors = [error.response.data.message];
                    }
                }).finally(() => this.loading = false);

            },
            goBack() {
                window.history.length > 1 ? this.$router.go(-1) : this.$router.push('/')
            }
        }
    }
</script>
<style>
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus
    input:-webkit-autofill,
    textarea:-webkit-autofill,
    textarea:-webkit-autofill:hover
    textarea:-webkit-autofill:focus,
    select:-webkit-autofill,
    select:-webkit-autofill:hover,
    select:-webkit-autofill:focus {
        border: 0;
        -webkit-text-fill-color: var(--v-primary-base);
        -webkit-box-shadow: 0 0 0px 1000px transparent inset;
        transition: background-color 5000s ease-in-out 0s;
        background: -webkit-linear-gradient(top,  rgba(255,255,255,0) 0%,rgba(0,174,255,0.04) 50%,rgba(255,255,255,0) 51%,rgba(0,174,255,0.03) 100%);
    }

</style>
