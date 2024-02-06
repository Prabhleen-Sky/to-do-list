<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/js/all.js"></script>

    <style>

    </style>

</head>

<body>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h1 style="text-align:center" class="m-2"> To Do List </h1>

                    <div class="card-body">
                        <form action="{{ route('store.task') }}" method="POST">
                            @csrf
                            <div class="d-flex justify-content-between">
                                <div class="">
                                    <input type="text" name="title" id="title" class="form-control"
                                        placeholder="New Task" style="width:150%" autocomplete="off" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Add Task</button>
                            </div>
                        </form>
                        <hr>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task List -->
        <div class="task-list-section mt-4 p-2" style="text-align: center;">
            @if (count($tasks) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <th>List of Tasks</th>
                            {{-- <th>Action</th> --}}
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                <tr>
                                    <td class="d-flex justify-content-between p-2 pl-4">
                                        {{ $task->title }}
                                        <div class="" style="display:inline-block">
                                            <button class='btn btn-danger btn-sm'
                                                onclick="confirmDelete('{{ $task->id }}')">
                                                Delete
                                            </button>
                                            <button class="btn btn-warning btn-sm ml-1" data-toggle="modal"
                                                data-target="#editModal" data-task-id="{{ $task->id }}"
                                                data-task-title="{{ $task->title }}">
                                                Edit
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="list-group">
                    <div class="list-group-item">
                        No tasks available
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editTaskForm" action="{{ route('edit.task') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="taskIdInput" name="taskId" autocomplete="off" required>
                        <div class="form-group">
                            <label for="taskTitleInput">Task Title</label>
                            <input type="text" class="form-control" id="taskTitleInput" name="taskTitle">
                        </div>
                        <button type="submit" class="btn btn-warning">Edit Task</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        //  {{-- Confirmation for Delete Method  --}}
        function confirmDelete(taskId) {
            var result = confirm("Are you sure you want to delete this Task?");
            if (result) {
                window.location.href = "{{ url('delete-task') }}/" + taskId;
            }
        }

        $('#editModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var taskId = button.data('task-id');
            var taskTitle = button.data('task-title');

            // console.log(taskId);
            // console.log(taskTitle);

            var modal = $(this);
            modal.find('.modal-body #taskIdInput').val(taskId);
            modal.find('.modal-body #taskTitleInput').val(taskTitle);
        });
    </script>

    <!-- Success Modal -->
    @if (session('success'))
        <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="successModalLabel">Success</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ session('success') }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Show the success modal on page load
            document.addEventListener('DOMContentLoaded', function() {
                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            });
        </script>
    @endif

    <!-- Error Modal -->
    @if (session('error') || $errors->any())
        <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="errorModalLabel">Error</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul>
                            {{ session('error') }}
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Show the error modal on page load
            document.addEventListener('DOMContentLoaded', function() {
                var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
            });
        </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
