<script setup>
import { inject } from 'vue';
const selectedTask = inject('selectedTask');
const updateTask = inject('updateTask');

const onUpdated = function(){
alert();
    updateTask()
}
</script>

<template>
    <label>Názov</label>
    <input type="text" name="name" v-model="selectedTask.name" required>
    <!--input type="text" name="name" :value="selectedTask.name" v-on:input="updateTask(selectedTask)" required-->
    <label>Popis</label>
    <input type="textarea" name="description" v-model="selectedTask.description" required>
    <label>Termín</label>
    <input type="date" name="due" v-model="selectedTask.due" required>
    <label>Splnený</label>
    <input type="checkbox" name="complete" v-model="selectedTask.completed">
</template>

<script>
export default {
    methods: {
        save: function() {
            axios.post('/task',{
                name: this.newtask,
                description: this.task.description,
            })
            .then(response => {
                this.reset();
                this.tasks.push(response.data.task);
                $("#add_task_model").modal("hide");
            })
            .catch(error => {
                this.errors = [];
                if (error.response.data.errors && error.response.data.errors.name) {
                    this.errors.push(error.response.data.errors.name[0]);
                }
                if (error.response.data.errors && error.response.data.errors.description) {
                    this.errors.push(error.response.data.errors.description[0]);
                }
            });
        },
        onUpdated: function(){
alert();
    updateTask()
}
    },
    mounted() {

    }
};
</script>
