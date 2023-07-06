
<template>
    <form ref="fields">
        <label>Názov</label>
        <input type="text" name="name" v-model="selectedTask.name" v-on:input="detailChanged" required>
        <label>Popis</label>
        <input type="textarea" name="description" v-model="selectedTask.description" v-on:input="detailChanged">
        <label>Termín</label>
        <input type="date" name="due" v-model="selectedTask.due" v-on:input="detailChanged" required>
        <label>Splnený</label>
        <input type="checkbox" name="completed" v-model="selectedTask.completed" v-on:input="detailChanged">
    </form>
    <em id="error">{{message}}</em>
</template>

<script>
import { inject, unref, watch } from 'vue';
export default {
    setup: function() {
        const selectedTask = inject('selectedTask');
        var timer = null;
        return {selectedTask, timer};
    },
    data: function() {
        return {
            message: ''
        }
    },
    methods: {
        syncBackend: function(vcomp) {
            vcomp.timer = null;
            if (vcomp.selectedTask.id)
                axios.patch('/task/' + vcomp.selectedTask.id, vcomp.selectedTask)
                .then((response) => Object.assign(vcomp.selectedTask, response.data.task))
                .catch(vcomp.logError);
            else axios.post('/task/', vcomp.selectedTask)
                .then((response) => Object.assign(vcomp.selectedTask, response.data.task))
                .catch(vcomp.logError);
        },
        detailChanged: function() {
            this.message = '';
            this.selectedTask.unsaved = true;
            if (this.timer)
                clearTimeout(this.timer);
            if (this.$refs.fields.checkValidity())
                this.timer = setTimeout(this.syncBackend, 2000, this);
            else this.message = 'Form is invalid';
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
    }
};
</script>
