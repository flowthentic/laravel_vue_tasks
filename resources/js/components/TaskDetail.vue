<template>
   <label></label>
   <input type="date" name="due" v-model="due" required>
</template>
<script>
export default {
    methods: {
        add: function(event) {
            axios.post('/task', {
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
                    if (error.response.data.errors && error.response.data.errors.description)
                    {
                        this.errors.push(error.response.data.errors.description[0]);
                    }
                });
            }



            this.tasks.push({name: this.newtask});
        }
    },
    mounted() {
    }
   }
</script>
