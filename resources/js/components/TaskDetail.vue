
<template>
    <form ref="fields">
        <label>Názov</label>
        <input type="text" name="name" v-model="selectedTask.name" v-on:input="detailChanged" required>
        <label>Popis</label>
        <input type="textarea" name="description" v-model="selectedTask.description" v-on:input="detailChanged">
        <label>Termín</label>
        <input type="date" name="due" v-model="selectedTask.due" v-on:input="detailChanged" required>
        <label>Splnený</label>
        <input type="checkbox" name="completed"
            v-model="selectedTask.completed" v-on:input="detailChanged"
            true-value="1" false-value="0">

        <strong v-for="error in selectedTask.errors" class="error">{{error}}</strong>
    </form>
</template>

<script>
import { inject } from 'vue';
export default {
    setup: function() {
        const selectedTask = inject('selectedTask');
        return {selectedTask};
    },
    methods: {
        detailChanged: function() {
            if (this.$refs.fields.checkValidity())
            {
                this.selectedTask.changed = Date.now();
                this.selectedTask.errors = null;
            }
            else this.selectedTask.errors = ['Polia vyznačené červenou sú povinné'];
        }
    }
};
</script>
