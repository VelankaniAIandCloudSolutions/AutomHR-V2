<div id="edit_task_board<" class="modal-dialog modal-dialog-centered" role="dialog">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit <?php echo ucfirst($board['task_board_name']);?> Board</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label>Task Board Name</label>
                        <input type="hidden" class="form-control" id="task_board_id<?php echo $board['task_board_id']?>" name="task_board_id" value="<?php echo $board['task_board_id'];?>">
                        <input type="text" class="form-control" id="task_board_name<?php echo $board['task_board_id']?>" name="task_board_name" value="<?php echo $board['task_board_name'];?>">
                        <input type="hidden" class="form-control" id="project_id<?php echo $board['task_board_id']?>" name="project_id" value="<?php echo $board['project_id'];?>">
                    </div>
                    <div class="form-group task-board-color">
                        <label>Task Board Color</label>
                        <div class="board-color-list">
                            <label class="board-control board-primary">
                                <input name="color<?php echo $board['task_board_id']?>" type="radio" class="board-control-input" data-class="primary" data-bc ="#fff5ec" value="#ff9b44" <?php echo($board['task_board_color']=="#ff9b44")?"checked":"";?>>
                                <span class="board-indicator"></span>
                            </label>
                            <label class="board-control board-success">
                                <input name="color<?php echo $board['task_board_id']?>" type="radio" class="board-control-input" data-class="success" data-bc="#edf7ee" value="#4caf50" <?php echo($board['task_board_color']=="#4caf50")?"checked":"";?>>
                                <span class="board-indicator"></span>
                            </label>
                            <label class="board-control board-info">
                                <input name="color<?php echo $board['task_board_id']?>" type="radio" class="board-control-input"  data-class="info" data-bc ="#e7f3fe" value="#42a5f5" <?php echo($board['task_board_color']=="#42a5f5")?"checked":"";?>>
                                <span class="board-indicator"></span>
                            </label>
                            <label class="board-control board-purple">
                                <input name="color<?php echo $board['task_board_id']?>" type="radio" class="board-control-input" data-class="purple" data-bc="#f1effd" value="#7460ee" <?php echo($board['task_board_color']=="#7460ee")?"checked":"";?>>
                                <span class="board-indicator"></span>
                            </label>
                            <label class="board-control board-warning">
                                <input name="color<?php echo $board['task_board_id']?>" type="radio" class="board-control-input" data-class="warning" data-bc ="##fdfcf3" value="#ffb300" <?php echo($board['task_board_color']=="#ffb300")?"checked":"";?>>
                                <span class="board-indicator"></span>
                            </label>
                            <label class="board-control board-danger">
                                <input name="color<?php echo $board['task_board_id']?>" type="radio" class="board-control-input" data-class="danger" data-bc ="#fef7f6" value="#ef5350" <?php echo($board['task_board_color']=="#ef5350")?"checked":"";?>>
                                <span class="board-indicator"></span>
                            </label>
                        </div>
                    </div>
                    <div class="m-t-20 text-center">
                        <button type="button" class="btn btn-primary btn-lg" id="task_board_edit" data-id="<?php echo $board['task_board_id'];?>" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing ">Save</button> 
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>