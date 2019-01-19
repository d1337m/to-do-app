<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="row">
                <div class="col-md-4" v-for="element,index in categories" :key="element.id">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{element.name}}</h4>
                        </div>
                        <div class="card-body card-body-dark">
                            <draggable :options="dragOptions" element="div" @end="changeOrder" v-model="element.tasks">
                                <transition-group :id="element.id">
                                    <div v-for="task,index in element.tasks" 
                                        :key="task.id+','+task.category_id+','+task.order" 
                                        class="transit-1" :id="task.id">
                                        <div class="small-card" @dblclick="enableModal(task.id)">
                                            <textarea id="text-area" v-if="task === editingTask" 
                                                class="text-input" @keyup.esc="endEditing(task)"
                                                @blur="endEditing(task)" v-model="task.name"></textarea>
                                            <label for="checkbox" v-if="task !== editingTask"
                                                @click="editTask(task)" >{{ task.name }}</label>
                                            <span class="badge badge-danger" v-if="task !== editingTask"
                                                @click="deleteTask(task.id)">X</span>
                                        </div>
                                    </div>
                                    <div class="small-card" :key="'addNew'">
                                        <button class="btn btn-basic" slot="footer" @click="addNew(index)">Add new task</button>
                                    </div>
                                </transition-group>
                            </draggable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <modal
            :show-modal="isModalVisible"
            @close-modal="closeModal()">
            <div slot="header">Task</div>
            <dashboard-form
                slot="body"
                :task-id="taskId"
                :ideas-len="ideasLast"
                :ongoing-len="ongoingLast"
                :completed-len="completedLast"
                @close-modal="closeModal()" />
        </modal>
    </div>
</template>
<script>
    import draggable from 'vuedraggable'
    import modal from './modal/DashboardModal'
    import DashboardForm from './DashboardForm'

    export default {
        components: {
            draggable,
            modal,
            DashboardForm
        },
        data(){
            return {
                categories : [],
                editingTask : null,
                isModalVisible: false,
                taskId: null,
                ideasLen: null,
                ongoingLen: null,
                completedLen: null,
                ideasLast: null,
                ongoingLast: null,
                completedLast: null
            }
        },
        methods : {
            enableModal(id) {
                this.taskId = id
                this.ideasLen = this.categories[0].tasks.length
                this.ongoingLen = this.categories[1].tasks.length
                this.completedLen = this.categories[2].tasks.length
                if (this.ideasLen) {
                    this.ideasLast = this.categories[0].tasks[this.ideasLen - 1].order
                }
                if (this.ongoingLen) {
                    this.ongoingLast = this.categories[1].tasks[this.ongoingLen - 1].order
                }
                if (this.completedLen) {
                    this.completedLast = this.categories[2].tasks[this.completedLen - 1].order
                }
                this.isModalVisible = true
            },
            loadCategories() {
                axios.get('api/category').then(response => {
                    response.data.forEach((data) => {
                        this.categories.push({
                            id : data.id,
                            name : data.name,
                            tasks : []
                        })
                    })
                    this.loadTasks()
                })
            },
            addNew(id) {
                let user_id = localStorage.getItem('id')
                let name = "New task"
                let category_id = this.categories[id].id
                let order = this.categories[id].tasks.length

                axios.post('api/task', {user_id, name, order, category_id}).then(response => {
                    this.categories[id].tasks.push(response.data.data)
                })

                this.loadTasks()
            },
            deleteTask(id) {
                axios.delete(`api/task/${id}`).then(response => {
                    this.loadTasks()
                })
            },
            loadTasks() {
                this.categories.map(category => {
                    axios.get(`api/category/${category.id}/tasks`).then(response => {
                        category.tasks = response.data
                    })
                })
            },
            changeOrder(data){
                let toTask = data.to
                let fromTask = data.from
                let task_id = data.item.id
                let category_id = fromTask.id == toTask.id ? fromTask.id : toTask.id
                let order = data.newIndex

                axios.patch(`api/task/${task_id}`, {order, category_id}).then(response => {

                })
            },
            endEditing(task) {
                this.editingTask =null

                axios.patch(`api/task/${task.id}`, {name: task.name}).then(response => {

                })
            },
            editTask(task) {
                this.editingTask = task
                this.$nextTick(() => {
                    document.getElementById('text-area').focus()
                })
            },
            closeModal() {
                this.isModalVisible = false
                this.loadTasks()
            }
        },
        mounted(){
            let token = localStorage.getItem('jwt')

            axios.defaults.headers.common['Content-Type'] = 'application/json'
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

            this.loadCategories()
        },
        computed: {
            dragOptions () {
              return  {
                animation: 1,
                group: 'description',
                ghostClass: 'ghost'
              };
            },
        },
        beforeRouteEnter (to, from, next) { 
            if ( ! localStorage.getItem('jwt')) {
                return next('login')
            }

            next()
        }
    }
</script>

<style scoped>
.card {
    border:0;
    border-radius: 0.5rem;
}
.transit-1 {
    transition: all 1s;
}
.small-card {
    padding: 1rem;
    background: #f5f8fa;
    margin-bottom: 5px;
    border-radius: .25rem;
    position: relative;
}
.card-body-dark{
    background-color: #ccc;
}
.badge{
    cursor: pointer;
    position: absolute;
    top: 0;
    right: 0;
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
