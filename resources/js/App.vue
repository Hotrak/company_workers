<template>
    <v-app id="inspire" :style="{background: $vuetify.theme.themes[theme].background}">
        <v-navigation-drawer
            v-model="drawer"
            app
            clipped
        >
            <v-list dense>
                <v-list-item
                    v-for="(item,index) in menuItems"
                    link
                    :key="index"
                    :to="item.link">
                    <v-list-item-action>
                        <v-icon>{{item.icon}}</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>{{item.text}}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

            </v-list>
        </v-navigation-drawer>

        <v-app-bar
            app
            color="primary"
            dark
            clipped-left
        >
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title class="font-weight-black display-1">PHP Junior</v-toolbar-title>
            <v-spacer/>

            <v-btn text v-if="isUserAuth" @click="logout">
                <v-icon left >mdi-logout-variant</v-icon>
                Выход
            </v-btn>
            <v-btn text v-else to="login">
                <v-icon left >mdi-login-variant</v-icon>
                Войти
            </v-btn>
        </v-app-bar>

        <v-main >
            <v-container
                fluid
            >
                <router-view></router-view>
            </v-container>
        </v-main>
<!--        <v-footer-->
<!--            color="primary"-->
<!--            app-->
<!--        >-->
<!--            <span class="white&#45;&#45;text">&copy; 2020</span>-->
<!--        </v-footer>-->
    </v-app>
</template>

<script>
    import {mapGetters,mapActions} from 'vuex'
    export default {
        name: "App",
        data: () => ({
            drawer: null,
        }),
        computed:{
            ...mapGetters(['isUserAuth']),
            theme(){
                return (this.$vuetify.theme.dark) ? 'dark' : 'light'
            },
            menuItems(){

                if(this.isUserAuth)
                    return [...this.guestMenuItems,...this.authorizeMenuItems];

                return this.guestMenuItems;
            },
            guestMenuItems(){
                return [
                    {
                        text:'Иерархия Сотрудников',
                        icon:'mdi-account-group',

                        link:{name:'workers-tree'},

                    }
                ]
            },
            authorizeMenuItems(){
                return [
                    {
                        text:'Сотрудники',
                        icon:'mdi-account',
                        link:{name:'workers'},
                    },
                ]
            }
        },
        methods:{
            ...mapActions(['logout']),
        }
    };
</script>
<style >
    .back {
        background-image: url(https://images.pexels.com/photos/186077/pexels-photo-186077.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1260);
        background-size: cover;
    }
    #inspire {
        /*background: none;*/
    }

    .theme--light.v-text-field>.v-input__control>.v-input__slot:before {
        border-color: #000000;
    }

    .theme--light.v-label {
        color: #000000;
    }

    .theme--light.v-input:not(.v-input--is-disabled) input, .theme--light.v-input:not(.v-input--is-disabled) textarea {
        color: #000000;
    }

</style>

