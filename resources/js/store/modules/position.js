export default {
    actions:{
        async fetchPositions({commit}){
            return await axios.get('/api/positions/')
        },

        async storePosition({commit},payload){
            return await axios.post('/api/positions/')
        },

        async updatePosition({commit},payload){
            return await axios.put('/api/positions/')
        },
    },
}
