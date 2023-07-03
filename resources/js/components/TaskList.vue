<template>
    <em  v-if="!tasks">Úkoly sa načítavajú</em>
    <em  v-else-if="tasks.length == 0">Žiadne úkoly na zobrazenie</em>
    <ol>
        <li v-for="task in tasks" :key="task.id">{{ task.name }}</li>
    </ol>
    <button v-on:click=add :disabled="newtask.length == 0">Add</button><input type="text" v-model="newtask">
</template>
<script>
export default {
    name: 'tasksApp',
    setup: function() {

    },
    data: function() {
        return {
            tasks: [],
            newtask: ''
        }
    },
    computed: {
        nameWithStatus: function() {

        }
    },
    created: function() {
        axios.get('/task')
            .then(response => {
                this.tasks = response.data.data;
            })
            .catch(error => {
                console.log("Error", error)
            })
    }
}
</script>


