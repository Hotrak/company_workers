import Vue from 'vue'
import store from '../store'
import VueRouter from 'vue-router'
import Workers from "../views/Workers";
import WorkersTree from "../views/WorkersTree";
import Login from "../views/Login";
import Register from "../views/Register";
import WorkerEditor from "../views/WorkerEditor";


Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',

    routes: [
        {
            path:'/',
            name:'home',
            component:WorkersTree,
        },
        {
            path:'/tree',
            name:'workers-tree',
            component:WorkersTree,
        },
        {
            path:'/workers',
            name:'workers',
            component: Workers,
            meta:{
                authorized:true,
            },
        },
        {
            path:'/workers/:id/edit',
            name:'worker',
            component: WorkerEditor,
            meta:{
                authorized:true,
            },
        },
        {
            path:'/workers/create',
            name:'worker-add',
            component: WorkerEditor,
            meta:{
                authorized:true,
            },
        },

        {
            path:'/login',
            name:'login',
            component:Login,
            meta:{
                guest:true,
            }
        },
        {
            path:'/register',
            name:'register',
            component:Register,
            meta:{
                guest:true,
            }
        }
    ],
});

router.beforeEach((to,from,next) => {
    if(to.matched.some(record=> record.meta.authorized)){
        if(store.getters['isUserAuth']){
            next()
        }else
            next({name:'login'});
    }else if(to.matched.some(record=> record.meta.guest)){
        if(!store.getters['isUserAuth']){
            next()
        }else
            next({name:'home'});
    }else
        next();

});


export default router;
