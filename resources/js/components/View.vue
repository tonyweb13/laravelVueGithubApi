<template>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-gradient-primary text-white">
                <h1 class="card-title">View Issue</h1>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="taskTitle" class="form-label">Issue Number:</label>
                        <input type="text" id="taskTitle" class="form-control" :value="`#${task.number}`" disabled />
                    </div>
                    <div class="col-md-4">
                        <label for="taskTitle" class="form-label">Title:</label>
                        <input type="text" id="taskTitle" class="form-control" v-model="task.title" disabled />
                    </div>

                    <div class="col-md-4">
                        <label for="taskStatus" class="form-label">Created:</label>
                        <input type="text" id="taskTitle" class="form-control" v-model="task.created_at" disabled />
                    </div>
                </div>
                <div class="row">
                    <div class="mt-4 col-md-6 text-start">
                        <router-link :to="{ name: 'Index' }" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left-circle"></i> Back to the Issues List
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #6610f2);
}
</style>

<script>
import { onMounted, reactive } from 'vue';
import { useRoute } from 'vue-router';

export default {
    setup () {
        const task = reactive({ number: '', title: '', created: '' });
        const route = useRoute();

        const getTask = async () => {
            try {
                const uri = `${import.meta.env.VITE_API_URL}/api/github/issues/${route.params.number}`;
                const response = await axios.get(uri);
                Object.assign(task, response.data);
            }
            catch (error) {
                errorMessage.value = 'Failed to fetch task details. Please try again.';
                console.error("Failed to fetch task:", error);
            }
        };

        onMounted(getTask);

        return {
            task,
        };
    }
}
</script>
