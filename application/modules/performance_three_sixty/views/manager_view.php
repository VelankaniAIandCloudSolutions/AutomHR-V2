<?php
$ratings = $this->db->get_where('competency_ratings')->row_array() ;

$user_details = $this->session->userdata();

$user_role_id = $this->session->userdata("role_id");

$employee_details = $this->db->get_where('users',array('id'=>$user_id))->row_array();
$designation = $this->db->get_where('designation',array('id'=>$employee_details['designation_id']))->row_array();
$account_details = $this->db->get_where('account_details',array('user_id'=>$user_id))->row_array();
$team_lead = $this->db->get_where('account_details',array('user_id'=>$employee_details['teamlead_id']))->row_array();
$teamlead = $this->db->get_where('account_details',array('user_id'=>$team_lead['user_id']))->row_array();

$self_rating = $rating = 'disabled';

$employee_id = $this->session->userdata('user_id');

if($employee_id == $employee_details['teamlead_id'])
{
    $rating ='';
}
elseif($employee_id == $user_id)
{
    $self_rating = '';
}
elseif($user_role_id == '1')
{
   $rating ='';
   $self_rating = '';
}
else{

}



?>
<!-- Content -->
<div class="content container-fluid">
    <div class="row">
        <div class="col-sm-8">
            <h4 class="page-title m-b-5">Manager/Admin Assestment</h4>
            <ol class="breadcrumb page-breadcrumb">
                <li>
                    <a href="#">Offer Accepted</a>
                </li>
                <li>
                    <a href="#">Completed Forms</a>
                </li>
                <li class="active">360 Performance</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-4">
                    <table class="table table-border user-info-table">
                        <tbody>
                            <tr>
                                <td>Employee</td>
                                <td class="text-right"> <?php echo $account_details['fullname']?> </td>
                            </tr>
                            <tr>
                                <td>Position</td>
                                <td class="text-right"> <?php echo $designation['designation']?> </td>
                            </tr>
<!-- <tr><td>Direct Manager</td><td class="text-right">
    <?php echo $teamlead['fullname']?></td></tr>	 -->
</tbody>
</table>
</div>
<div class="col-sm-8">
    <div class="join-year">
        <span>Year</span>
        <!-- <select class="select form-control"><option>2019</option></select> -->
        <form name="year_filter" id='year_filter' method="post" action="
        <?php echo base_url('performance_three_sixty/show_performance_three_sixty/').$this->uri->segment(3);?>">
        <div class="row">
            <div class="col-md-8">
                <select class="select form-control" id="year" name="year"> <?php 
                $current_year = date("Y");

                $selected_year = $year;

$years_range = range($current_year - 5, $current_year + 5); // Create range of years
rsort($years_range); // Sort years in descending order

foreach ($years_range as $year_val) {
?> <option value="
<?php echo $year_val;?>" <?php if ($year_val == $selected_year) { echo "selected"; } ?>> <?php echo ($year_val-1).' - '.$year_val;?> </option> <?php
}
?> </select>
</div>




<div class="col-md-4">
    <input name="year_filter_btn" id="year_filter_btn" type="submit" class="btn-primary btn-md" value="Filter">
</div>
</div>
</form>
</div>
</div>
</div>
<div class="performance-wrap"> <?php if(!empty($performances_360)){ 
    $total_count = count($performances_360)+1;
    $count =1 ; ?> </div> <?php } ?> <div class="performance-wrap">
        <h3 class="m-b-20">Competencies</h3>
        <div class="performance-box comp-box m-b-0">

            <form name="competency_form" id="competency_form" method="post" action="<?php echo base_url('performance_three_sixty/manager_competence_rating');?>">

                <div class="table-responsive">


                    <table class="table performance-table">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 20%;">Competencies</th>
                                <th class="text-center" style="width: 10%;">Self Rating</th>
                                <th class="text-center" style="width: 10%;">Rating</th>
                                <!-- <th class="text-center" style="width: 85px;">Feedback</th> -->

                                <th class="text-center" style="width: 20%;">What I did</th>
                                <th class="text-center" style="width: 20%;">What I could have done better</th>
                                <th class="text-center" style="width: 20%;">What I didn’t do</th>
                                <th class="text-center" style="width: 20%;">Manager Comment</th>

                                <!-- <th class="text-center" style="width: 85px;"></th> -->
                            </tr>
                            </thead> <?php $performance_competency = $this->db->order_by('competency','ASC')->get('performance_competency')->result_array(); ?> 
                            <tbody> <?php if(! empty($performance_competency)){ 

                                foreach ($performance_competency as $performance_competencies) {

                                ?> <tr>
                                   <input type="hidden" name="user_id" value="<?php echo $this->uri->segment(3);?>">
                                   <td style="width: 20%;">
                                    <?php echo $performance_competencies['competency'];?>

                                    <input type="hidden" class="form-control" readonly name="competencies[]" required="" 
                                    value="<?php echo $performance_competencies['id'];?>">

                                    <input type="hidden" class="form-control" name="teamlead_id[]" required="" 
                                    value="<?php echo $employee_details['teamlead_id'];?>">
                                </td>
                                <td style="width: 10%;">
                                    <select class="form-control select " name="self_rating[]" disabled="disabled">
                                        <option value="">Not rated</option> <?php if(isset($ratings) && !empty($ratings)){                                                          
                                            $rating_no = explode('|',$ratings['rating_no']);
                                            $rating_value = explode('|',$ratings['rating_value']);
                                            $definition = explode('|',$ratings['definition']);
                                            $a= 1;
                                            $selected='';
                                            for ($i=0; $i 
                                                <count($rating_no) ; $i++) {
                                                if(!empty($rating_no[$i])){
                                                    $self_rating_id=$this->db->where('competencies',$performance_competencies['id'])->where('user_id',$employee_details['id'])->get('dgt_competencies')->row_array();

                                                    if(!empty($self_rating_id['self_rating'])){
                                                        if($rating_no[$i]==$self_rating_id['self_rating'])
                                                        {
                                                            $selected='selected';
                                                        }
                                                        else
                                                        {
                                                            $selected='';
                                                        }
                                                    } else{
                                                        $selected='';
                                                    }
                                                ?> <option value="
                                                <?php echo $rating_no[$i];?>" <?php echo $selected;?>> <?php echo $rating_value[$i];?> </option> <?php } } } else { ?> <option value="">Ratings Not Found</option> <?php } ?>
                                            </select>
                                        </td>
                                        <td style="width: 10%;">
                                            <select class="form-control select " name="rating[]" data-id="
                                            <?php echo $performance_competencies['id'] ?>" data-userid="
                                            <?php echo $employee_details['id'];?>">
                                            <option value="">Not rated</option> <?php if(isset($ratings) && !empty($ratings)){                                                      
                                                $rating_no = explode('|',$ratings['rating_no']);
                                                $rating_value = explode('|',$ratings['rating_value']);
                                                $definition = explode('|',$ratings['definition']);
                                                $a= 1;
                                                $selected='';
                                                for ($i=0; $i 
                                                    <count($rating_no) ; $i++) {
                                                    if(!empty($rating_no[$i])){
                                                        $self_rating_id=$this->db->where('competencies',$performance_competencies['id'])->where('user_id',$employee_details['id'])->get('dgt_competencies')->row_array();

                                                        if(!empty($self_rating_id['rating'])){
                                                            if($rating_no[$i]==$self_rating_id['rating'])
                                                            {
                                                                $selected='selected';
                                                            }
                                                            else
                                                            {
                                                                $selected='';
                                                            }
                                                        } else{
                                                            $selected='';
                                                        }
                                                    ?> <option value="
                                                    <?php echo $rating_no[$i];?>" <?php echo $selected;?>> <?php echo $rating_value[$i];?> </option> <?php } } } else { ?> <option value="">Ratings Not Found</option> <?php } ?>
                                                </select>
                                            </td> 
                                            <?php 

                                            $feedback=$this->db->where('competencies',$performance_competencies['id'])->where('user_id',$employee_details['id'])->where('YEAR(created_by)', trim($year))->get('dgt_competencies')->row_array();
                                            ?> 
                                            <td style="width: 30%;" class="text-center">

                                                <textarea name="Appraisee_Comments[]" <?php echo $rating.' '. $self_rating;?> ><?php echo $feedback['Appraisee_Comments']?></textarea>

                                            </td>
                                            <td style="width: 30%;" class="text-center">
                                                <textarea name="Appraiser_Comments[]" <?php echo $rating.' '. $self_rating;?>><?php echo $feedback['Appraiser_Comments']?></textarea>
                                            </td>
                                            <td style="width: 20%;" class="text-center">
                                                <textarea name="HOD_Comments[]" <?php echo $rating.' '. $self_rating;?>><?php echo $feedback['HOD_Comments']?></textarea>
                                            </td>
                                            <td style="width: 20%;" class="text-center">
                                                <textarea name="reporting_manager_comment[]" ><?php echo $feedback['reporting_manager_comment']?></textarea>
                                            </td>
                                            <!-- <td><button type="button" class="btn btn-white add_competency" data-toggle="tooltip" data-original-title="Add Competency"><i class="fa fa-plus-circle"></i></button></td> -->
                                        </tr>
                                    <?php } }?>
                                </tbody> 
                            </table>

                        </div>
                        <br><br>
                        <div class="row">
                            <!-- Calculation fo average -->
                            <!-- <div class="col-md-4">
                                <label>KPO Score</label>
                                <input name="kpo_score_by_manager" id="kpo_score_by_manager" type="text" class="form-control" value='<?php echo $kpo_score_by_manager;?>'>
                            </div> -->
                            <div class="col-md-4">
                                <label>Self Overall Competency</label>
                                <input type="text" class="form-control" readonly value='<?php echo $self_average;?>'>
                            </div>
                            <div class="col-md-4">
                                <label>Overall Score</label>
                                <input type="text" class="form-control" readonly value='<?php echo $overall_average;?>'>
                            </div>
                        </div>
                        <br><br>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="submit" name="manager_ratting" id ="manager_ratting" class="btn btn-primary btn-md">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->
        <!-- <div id="opj_feedback" class="modal center-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Feedback</h4>
                    </div>
                    <div class="modal-body">
                        <ul class="review-list">
                            <li>
                                <div class="review">
                                    <div class="review-author">
                                        <img class="avatar" alt="User Image" src="assets/img/user.jpg">
                                    </div>
                                    <div class="review-block">
                                        <div class="review-by">
                                            <span class="review-author-name">Mark Boydston</span>
                                        </div>
                                        <p>With great power comes great capability</p>
                                        <span class="review-date">Feb 6, 2019</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- /View Feedback Modal --> <?php $performances_360 = $this->db->select()
        ->from('performance_360')
        ->get()->result_array();

        foreach ($performances_360 as $performance_360) { ?>
            <!-- Add Feedback Modal -->
            <div id="add_opj_feedback
            <?php echo $performance_360['id']?>" class="modal center-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Feedback</h4>
                    </div>
                    <form class="form-horizontal" action="
                    <?=base_url()?>performance_three_sixty/three_sixty_feedback" method="POST">
                    <div class="modal-body"> <?php $feed_backs = $this->db->select()
                    ->from('three_sixty_feedback')
                    ->where('goal_id',$performance_360['id'])
                    ->get()->result_array();  
// echo ($this->db->last_query());
                    if(!empty($feed_backs)){
                    ?> <ul> <?php foreach ($feed_backs as $feed_back) { ?> <li> <?php echo $feed_back['feed_back'];?> </li> <?php }?> </ul> <?php }?> <div class="form-group">
                        <label>Write Feedback</label>
                        <textarea rows="4" class="form-control" name="feed_back"></textarea>
                        <input type="hidden" name="goal_id" value="
                        <?php echo $performance_360['id']?>">
                        <input type="hidden" name="user_id" value="
                        <?php echo $performance_360['user_id']?>">
                    </div>
                    <div class="submit-section">
                        <input type="submit" value="Submit" class="btn btn-primary submit-btn">
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div> <?php } ?>
    <!-- /Add Feedback Modal --> <?php $competencies = $this->db->select()
    ->from('competencies')
    ->get()->result_array();
// echo $competencies; exit
    if(!empty($competencies)){
        foreach ($competencies as $competence) { ?>
            <!-- Add Feedback Modal -->
            <div id="add_com_feedback
            <?php echo $competence['id'];?>" class="modal center-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Feedback</h4>
                    </div>
                    <form class="form-horizontal" action="
                    <?=base_url()?>performance_three_sixty/competencies_feedback" method="POST">
                    <div class="modal-body"> <?php $feed_backs = $this->db->select()
                    ->from('competencies_feedback')
                    ->where('competencies_id',$competence['id'])
                    ->get()->result_array();  
// echo ($this->db->last_query());
                    if(!empty($feed_backs)){
                    ?> <ul> <?php foreach ($feed_backs as $feed_back) { ?> <li> <?php echo $feed_back['feed_back'];?> </li> <?php }?> </ul> <?php }?> <div class="form-group">
                        <label>Write Feedback</label>
                        <textarea rows="4" class="form-control" name="feed_back"></textarea>
                        <input type="hidden" name="competencies_id" value="
                        <?php echo $competence['id']?>">
                        <input type="hidden" name="user_id" value="
                        <?php echo $competence['user_id']?>">
                    </div>
                    <div class="submit-section">
                        <input type="submit" value="Submit" class="btn btn-primary submit-btn">
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div> <?php }
} ?>
<!-- /Add Feedback Modal -->
<div class="sidebar-overlay" data-reff="#sidebar"></div>