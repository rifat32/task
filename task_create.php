<?php 
include 'db_connect.php';
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM task_list where id = ".$_GET['id'])->fetch_array();
    foreach($qry as $k => $v){
        $$k = $v;
    }
}
?>
<div class="container-fluid">
    <form action="" id="manage-task">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="col-lg-12">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <?php if ($_SESSION['login_type'] != 3): ?>
                        <div class="card-tools">
                            <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_project"><i class="fa fa-plus"></i> Add New project</a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <label for="projectSelect">Select a Project:</label>
                    <select id="projectSelect" class="form-select" name="project_id">
                        <option value="" disabled selected>Select a project</option>
                        <?php
                        $qry = $conn->query("SELECT * FROM project_list $where ORDER BY name ASC");
                        while ($row = $qry->fetch_assoc()):
                            ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo ucwords($row['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                    <div id="projectDetails">
                        <!-- Project details will be displayed here -->
                    </div>
                    <div class="form-group">
                        <label for="">Task</label>
                        <input type="text" class="form-control form-control-sm" name="task" value="<?php echo isset($task) ? $task : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <textarea name="description" id="" cols="30" rows="10" class="summernote form-control">
                            <?php echo isset($description) ? $description : '' ?>
                        </textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Status</label>
                        <select name="status" id="status" class="custom-select custom-select-sm">
                            <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Pending</option>
                            <option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>On-Progress</option>
                            <option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>Done</option>
                        </select>
                    </div>
                    <button type="submit" id="submitBtn" class="btn btn-success">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Include jQuery and Summernote libraries if not already included -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>

<script>
    $(document).ready(function(){
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                [ 'style', [ 'style' ] ],
                [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
                [ 'fontname', [ 'fontname' ] ],
                [ 'fontsize', [ 'fontsize' ] ],
                [ 'color', [ 'color' ] ],
                [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
                [ 'table', [ 'table' ] ],
                [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
            ]
        });

        $('#manage-task').submit(function(e){
            e.preventDefault();
            start_load();
            $.ajax({
                url:'ajax.php?action=save_task',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success:function(resp){
                    if(resp == 1){
                        alert_toast('Data successfully saved', "success");
                        setTimeout(function(){
                            location.reload();
                        },1500);
                    }
                }
            });
        });
    });
</script>

<style>
    /* Add your custom CSS styles here */

    .form-select {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    table p {
        margin: unset !important;
    }

    table td {
        vertical-align: middle !important;
    }

    /* Style for the submit button */
    #submitBtn {
        margin-top: 10px;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    #submitBtn:hover {
        background-color: #45a049;
    }
</style>