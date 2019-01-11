<template>
	<div class="container">
		<div id="inline">
			<h6 class="task-create">Task created by {{ task.user.name }}</h6>
			<span class="category-button">
				<select v-model="selectedCategory">
					<option v-for="category in categories" :value="category">{{ category.name }}</option>
				</select>
			</span>
		</div>
		<hr>
		<div>
			<textarea  class="text-input" v-model="task.name" />
		</div>
		<hr>
		<button class="btn btn-primary" @click="edit(task)">
			Save
		</button>
	</div>
</template>

<script>
export default {
	name: 'DashboardForm',
	props: {
		taskId: {
			type: Number,
			default: null
		},
		ideasLen: {
			type: Number,
			default: null
		},
		ongoingLen: {
			type: Number,
			default: null
		},
		completedLen: {
			type: Number,
			default: null
		}
	},
	data() {
		return {
			categories: [],
			task: { user: {} },
			selectedCategory: {},
			order: null
		}
	},
	watch: {
		taskId: function (newVal, oldVal) {
			if (newVal) { return this.onCreate() }
		}
	},
	methods: {
		onCreate() {
			this.getTask(this.taskId)
			this.loadCategories()
		},
		getTask(taskId) {
			axios.get(`api/task/${taskId}`).then(response => {
                    this.task = response.data
                })
		},
		edit(task) {
			if (this.selectedCategory.id != task.category_id && this.selectedCategory.id === 1) {
				this.order = this.ideasLen + 1
			} else if (this.selectedCategory.id != task.category_id && this.selectedCategory.id === 2) {
				this.order = this.ongoingLen + 1
			} else if (this.selectedCategory.id != task.category_id && this.selectedCategory.id === 3) {
				this.order = this.completedLen + 1
			} else {
				this.order = task.order
			}
			axios.patch(`api/task/${task.id}`, {name: task.name, category_id: this.selectedCategory.id, order: this.order}).then(response => {
				this.$emit('close-modal')
			})
		},
        loadCategories() {
        	this.categories = []
            axios.get('api/category').then(response => {
                response.data.forEach((data) => {
                    this.categories.push({
                        id : data.id,
                        name : data.name,
                    })
                    if (data.id === this.task.category_id) {
                    	this.selectedCategory = {id: data.id, name: data.name}
                    }
                })
            })
        }
	}
}
</script>

<style scoped>
#inline {
	display: flex;
}
.task-create {
	width: 50%;
	margin-right: auto;
}
.category-button { 
	width: 50%;
	margin-left: auto;
}
.btn {
	float: right;
}
textarea {
    overflow: visible;
    outline: 1px dashed black;
    border: 0;
    padding: 6px 0 2px 8px;
    width: 100%;
    height: 100%;
    resize: none;
}
</style>