<template>
   <label>Názov</label>
   <input type="text" name="name" v-model="modelValue.name" required>
   <label>Popis</label>
   <input type="textarea" name="description" v-model="modelValue.description" required>
   <label>Termín</label>
   <input type="date" name="due" v-model="modelValue.due" required>
   <label>Splnený</label>
   <input type="checkbox" name="complete" v-model="modelValue.completed">
</template>
<script setup>
defineProps(['modelValue'])
defineEmits(['update:modelValue'])
</script>
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
        }
    },
    mounted() {

    }
};
</script>
