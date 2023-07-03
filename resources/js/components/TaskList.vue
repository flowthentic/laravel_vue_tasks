<template>
    <em  v-if="!tasks">Úkoly sa načítavajú</em>
    <em  v-else-if="tasks.length == 0">Žiadne úkoly na zobrazenie</em>
    <ol v-else>
        <li v-for="task in tasks" :key="task.id">{{ task.name + (task.unsaved == true ? " *" : "") }}</li>
    </ol>
    <button v-on:click="createTask">Add</button>
    <task-detail v-if="selected" v-model="selected"/>
</template>
<script>
export default {
    name: 'tasksApp',
    setup: function() {

    },
    data: function() {
        return {
            tasks: null,
            selected: false,
            newtask: null
        }
    },
    computed: {
        nameWithStatus: function() {
        }
    },
    methods: {
        createTask: function() {
            this.selected = {
                name: '',
                description: '',
                due: '',
                complete: false,
                unsaved: true
            };
            this.tasks.push(this.selected);
        }
    },
    created: function() {
        axios.get('/task')
            .then(response => {
                this.tasks = response.data.tasks;
            })
            .catch(error => {
                console.log("Error", error)
            })
    }
}
</script>


