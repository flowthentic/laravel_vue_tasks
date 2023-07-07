
<template>
    <header>
        <h1>Moje úkoly</h1>
        <em v-if="!tasks">Úkoly sa načítavajú</em>
        <button v-else v-on:click="createTask">Nový úkol</button>
        <em v-if="tasks.length == 0">Žiadne úkoly na zobrazenie</em>
    </header>
    <ol v-if="tasks">
        <li v-for="(task, number) in tasks">
            <input v-model="selectedTask" v-bind:value="task"
                type="radio" name="task" v-bind:id="'task'+number">
            <label v-bind:for="'task'+number">{{ task.name + (task.changed ? " *" : "") }}</label>
        </li>
    </ol>
    <task-detail v-if="selectedTask"/>
</template>

<script>
import { provide, ref } from 'vue';
export default {
    setup: function() {
        const selectedTask = ref(false);
        provide('selectedTask', selectedTask);
        return { selectedTask };
    },
    data: function() {
        return {
            tasks: null,
            timerID: null
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
                changed: true,
                error: false
            });
            this.selectedTask = this.tasks.at(-1);
        },
        syncBackend: function(list) {
            //loop through all tasks in the list
            for (let i = 0; i < list.length; i++)
            {
                if (list[i].changed == false)
                    continue; //not changed since last save
                else if (Date.now() - list[i].changed < 1000)
                    continue; //changed, but probably still typing
                //else now we can proceed to actual save logic

                else if (list[i].id)
                    //patch tasks if already in the api
                    axios.patch('/task/' + list[i].id, list[i])
                    .then((response) => Object.assign(list[i], response.data.task))
                    .catch((error) => list[i].error = error.response.data.message);
                else axios.post('/task/', list[i])
                    //else post new tasks
                    .then((response) => Object.assign(list[i], response.data.task))
                    .catch((error) => list[i].error = error.response.data.message);
            }
        },
    },
    created: function() {
        axios.get('/task')
            .then(response => {
                this.tasks = response.data.tasks;
                this.timerID = setInterval(this.syncBackend, 1500, this.tasks);
            })
            .catch(error => {
                console.log("Unable to reach the API", error)
            })
    },
    unmount: function() {
        clearInterval(this.timerID);
    }
}
</script>


