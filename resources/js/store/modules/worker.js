export default {
    actions:{
        async fetchWorkers({commit},payload){
            return await axios.get('/api/workers/',{
                params:payload.params,
            })
        },
        async fetchWorkersTree({commit},payload){
            return await axios.get('/api/workers/tree',{
                params:payload.params,
            })
        },
        async fetchWorkersTreeChildren({commit},payload){
            return await axios.get(`/api/workers/${payload.id}/children`)
        },

        async fetchWorker({commit},payload){
            return await axios.get(`/api/workers/${payload.id}`)
        },
        async storeWorker({commit},payload){
            return await axios.post(`/api/workers`,payload,{
                    headers: {'Content-Type': 'multipart/form-data'}
            })
        },
        async updateWorker({commit},{id,data}){
            return await axios.post(`/api/workers/${id}?_method=put`,data,{
                    headers: {'Content-Type': 'multipart/form-data'}
                })
        },
        async updateWorkerBoss({commit},payload){
            return await axios.put(`/api/workers/boss/update`,payload)
        },
        async deleteWorker({commit},payload){
            return await axios.delete(`/api/workers/${payload.id}`,payload)
        },
    },
}
