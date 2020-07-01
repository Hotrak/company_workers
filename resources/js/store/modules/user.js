// import axios from 'axios'
import router from "../../router";
// axios.defaults.baseURL = 'http://127.0.0.1:8000/api/';
export default{
    state:{
        user:null,
        token:null,
        isAdmin:true,
        isAuth:false,

    },
    actions:{
        async login(context,payload){
            return await axios.post('/api/login',payload)
        },
        async register (context,payload){
            return await axios.post('/api/register',payload)
        },
        logout({commit}){
            axios.post('/api/logout').then(response=>{
                console.log("FROM_LOGOUT:"+response.data);
            })

            commit('setToken',null);
            commit('setUser',null);
            router.push('/login');
            console.log('USER LOGOUT');
        },
        async checkUserAuth({commit,state},token){

            if(token){
                commit('setToken',token);
            }
            if(!state.token)
                return;

            await axios.get('user').then(response=>{
                console.log('CheckUserAuth:'+response.data);
                commit('setUser',response.data['user']);
                commit('setSettings',response.data['settings']);

            }).catch(error=>{
                if(error.response.status === 422){
                    console.log(error.response.data);
                }else if(error.response.status === 401){
                    commit('setToken',null);
                }


            })
        }

    },
    mutations:{
        setUser(state,user){
            state.user = user;
        },
        setToken(state,token){
            if(token){
                axios.defaults.headers.common['Authorization'] =`Bearer ${token}`;
            }else{
                axios.defaults.headers.common['Authorization'] ='';
            }

            // console.log("TOKEN_FROM_SUB:"+token);
            state.token = token;
        }
    },
    getters:{
        getUser:(state)=>state.user,
        getToken:(state)=>state.token,
        isUserAuth(state){
            if(state.user === null)
                return false;
            return true;
        },
    }

}
