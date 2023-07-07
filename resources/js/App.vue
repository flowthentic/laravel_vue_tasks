
<template>
    <header>
        <h1>Moje úkoly</h1>
        <em v-if="!tasks">Úkoly sa načítavajú</em>
        <button v-else v-on:click="createTask">Nový úkol</button>
        <em v-if="tasks.length == 0">Žiadne úkoly na zobrazenie</em>
    </header>
    <ol v-if="tasks">
        <li v-for="task in tasks" v-on:click="selectTask(task, this)">{{ task.name + (task.unsaved ? " *" : "") }}</li>
    </ol>
    <task-detail v-if="selectedTask"/>
</template>

<script>
import { provide, ref } from 'vue';
export default {
    setup: function() {
        const selectedTask = ref(false);
        provide('selectedTask', selectedTask);

        const selectTask = function (t) {
            //Console.log(t);
        };
        return { selectedTask };
    },
    data: function() {
        return {
            tasks: null
        }
    },
    methods: {
        createTask: function() {
            this.tasks.push({
                id: null,
                name: '',
                description: '',
                due: '',
                completed: false,
                unsaved: true
            });
            this.selectedTask = this.tasks.at(-1);
        },
        selectTask: function(task) {
            this.selectedTask = task;
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


