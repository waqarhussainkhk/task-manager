@extends('layouts.app')

@section('content')
    <h1>Task Manager</h1>

    <!-- Search Bar -->
    <form action="{{ route('tasks.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search tasks..." value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
        </div>
    </form>

    <!-- Create Task Button -->
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createTaskModal">
        Create New Task
    </button>

    <!-- Task List -->
    <div class="task-list">
        @foreach($tasks as $task)
            <div class="card mb-3" data-task-id="{{ $task->id }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $task->title }}</h5>
                    <p class="card-text">{{ $task->description }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge badge-{{ $task->status == 'Completed' ? 'success' : 'warning' }}">
                            {{ $task->status }}
                        </span>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-primary edit-task" 
                                    data-toggle="modal" 
                                    data-target="#editTaskModal"
                                    data-task="{{ json_encode($task) }}">
                                Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-danger delete-task" 
                                    data-toggle="modal" 
                                    data-target="#deleteTaskModal"
                                    data-task-id="{{ $task->id }}">
                                Delete
                            </button>
                        </div>
                    </div>
                    <small class="text-muted">Created: {{ $task->created_at->format('M d, Y') }}</small>
                </div>
            </div>
        @endforeach
    </div>

    {{ $tasks->links() }}

    <!-- Create Task Modal -->
    @include('tasks.modals.create')

    <!-- Edit Task Modal -->
    @include('tasks.modals.edit')

    <!-- Delete Task Modal -->
    @include('tasks.modals.delete')
@endsection

@section('scripts')
<script>
$(function() {
    // Drag and drop functionality
    $(".task-list").sortable({
        update: function(event, ui) {
            let tasks = [];
            $('.card').each(function(index) {
                tasks.push({
                    id: $(this).data('task-id'),
                    order: index
                });
            });

            $.ajax({
                url: '{{ route("tasks.update-order") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    tasks: tasks
                }
            });
        }
    });

    // Edit task
    $('.edit-task').click(function() {
        let task = $(this).data('task');
        $('#editTaskForm').attr('action', `/tasks/${task.id}`);
        $('#editTaskTitle').val(task.title);
        $('#editTaskDescription').val(task.description);
        $('#editTaskStatus').val(task.status);
    });

    // Delete task
    $('.delete-task').click(function() {
        let taskId = $(this).data('task-id');
        $('#deleteTaskForm').attr('action', `/tasks/${taskId}`);
    });
});
</script>
@endsection