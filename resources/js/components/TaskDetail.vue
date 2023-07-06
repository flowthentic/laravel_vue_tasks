
<template>
    <form id="fields">
        <label>Názov</label>
        <input type="text" name="name" v-model="selectedTask.name" required>
        <label>Popis</label>
        <input type="textarea" name="description" v-model="selectedTask.description">
        <label>Termín</label>
        <input type="date" name="due" v-model="selectedTask.due" required>
        <label>Splnený</label>
        <input type="checkbox" name="completed" v-model="selectedTask.completed">
    </form>
    <em id="error">{{message}}</em>
</template>

<script>
import { inject, unref } from 'vue';
export default {
    setup: function() {
        const selectedTask = inject('selectedTask');
        return {selectedTask};
    },
    data: function() {
        return {
            message: ''
        }
    },
    methods: {
        logError: function(error) {
            this.errors = [];
            if (error.response.data.errors.name) {
                this.errors.push(error.response.data.errors.name[0]);
            }
            if (error.response.data.errors.description) {
                this.errors.push(error.response.data.errors.description[0]);
            }
        }
    },
    mounted() {

    },
    created: function(){
        const saver = setInterval((vcomp) => {

            vcomp.message = '';
            if (!document.getElementById('fields').checkValidity())
                vcomp.message = 'Form is invalid';
            else if (!vcomp.selectedTask.unsaved); //pass
            else if (vcomp.selectedTask.id)
                axios.patch('/task/' + vcomp.selectedTask.id, vcomp.selectedTask)
                .then((response) => vcomp.selectedTask = response.data.task)
                .catch(vcomp.logError);
            else axios.post('/task/', vcomp.selectedTask)
                .then((response) => Object.assign(vcomp.selectedTask, response.data.task))
                .catch(vcomp.logError);
        }, 1000, this);
    }
};
</script>
