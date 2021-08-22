import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        deleteModalObj: {
            showDeleteModal: false,
            dataUrl: '',
            data: null,
            deletingIndex: -1,
            isDeleted: false
        }
    },
    getters: {
        getDeleteModalObj(state){
            return state.deleteModalObj;
        }
    },
    mutations: {
        setDeleteModal(state, data){
            const deleteModalObj = {
                showDeleteModal: false,
                dataUrl: '',
                data: null,
                deletingIndex: -1,
                isDeleted: data
            }
            state.deleteModalObj = deleteModalObj
            
        },
        setDeleteModalObj(state, data){
            state.deleteModalObj = data
        }
    },
    actions: {

    }
});