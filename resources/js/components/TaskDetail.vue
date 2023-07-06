
<template>
    <form ref="fields">
        <label>Názov</label>
        <input type="text" name="name" v-model="selectedTask.name" required>
        <label>Popis</label>
        <input type="textarea" name="description" v-model="selectedTask.description">
        <label>Termín</label>
        <input type="date" name="due" v-model="selectedTask.due" required>
        <label>Splnený</label>
        <input type="checkbox" name="complete" v-model="selectedTask.completed">
    </form>
    <em id="error">{{message}}</em>
</template>

<script>
import { inject } from 'vue';
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
        saveTask: function() {

        },
        updateTask: function() {
            axios.patch('/task/' + this.update_task.id, {
                name: this.update_task.name,
                description: this.update_task.description,
            })
            .then(response => {
                $("#update_task_model").modal("hide");
            })
            .catch(error => {
                this.errors = [];
                if (error.response.data.errors.name) {
                    this.errors.push(error.response.data.errors.name[0]);
                }
                if (error.response.data.errors.description) {
                    this.errors.push(error.response.data.errors.description[0]);
                }
            });
        },
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
        const saver = setInterval((vcomponent) => {

            this.message = '';
            if (!vcomponent.$refs.fields.checkValidity())
                this.message = 'Form is invalid';
            else if (!this.selectedTask.unsaved); //pass
            else if (this.selectedTask.id)
                axios.patch('/task/' + this.selectedTask.id, this.selectedTask)
                .then((response) => this.selectedTask = response.data.task)
                .catch(this.logError);
            else axios.post('/task/', this.selectedTask)
                .then((response) => this.selectedTask = response.data.task)
                .catch((error) => this.message = error.response.data.errors.name[0]);
        }, 1000, this);
    }
};
</script>
