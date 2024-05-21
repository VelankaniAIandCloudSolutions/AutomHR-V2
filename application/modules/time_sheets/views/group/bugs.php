<section>
  <?php
    if (!User::is_client() || Project::setting('show_project_bugs',$project_id)) { 
      $sub_group = isset($_GET['view']) ? $_GET['view'] : '';
      if($sub_group == ''){
        $data['project_id'] = $project_id;
        $this->load->view('group/sub_group/bugs',$data);
      }else{
        $data['project_id'] = $project_id;
        $this->load->view('group/sub_group/'.$sub_group,$data);
      }
    }
  ?>
</section>