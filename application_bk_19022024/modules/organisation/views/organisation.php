<?php 
$all_grades = $this->db->get('grades')->result_array();
$result = array(); 
$result['class'] = 'go.GraphLinksModel';
$result['linkFromPortIdProperty'] = 'fromPort';
$result['linkToPortIdProperty'] = 'toPort';
$result['nodeDataArray'][] = array(
      'category' =>'yellowbox',
      'text'     =>'Yellow Box',
      'key'      =>'-1',
      'loc'      => '',
      'name'     => '',
      "department" =>config_item('company_name'),
      "position"=>"",
      "picture" =>base_url()."assets/images/".config_item('company_logo')
    );




$i=0;
foreach ($all_grades as $grades) {
  $des_grade = $this->db->select('*')
                       ->from('designation D')
                       ->join('users U','U.designation_id = D.id')
                       ->join('account_details AD','AD.user_id = U.id')
                       ->where('D.grade',$grades['grade_id'])
                       ->where('U.activated',1)
                       ->where('U.banned',0)
                       ->where('AD.branch_id',$this->session->userdata('branch_id'))
                       ->get()->result_array();


  foreach($des_grade as $des)
  {
    if($des['avatar'] == '')
    {
      $pro_pic = base_url().'assets/avatar/default_avatar.jpg';
    }else{
      $pro_pic = base_url().'assets/avatar/'.$des['avatar'];
    }
      if($i == 0){
        $res = array(
          'category' =>'greenbox',
          'text'     =>'Green Box',
          'key'      =>'-'.$des['user_id'],
          'loc'      => '',
          'name'     => $des['fullname'],
          "department" =>$des['deptname'],
          "position"=>$des['designation'],
          "picture" =>$pro_pic
        );
        if($des['teamlead_id'] == 0){  
          $posi = array(
                  'from' => '-1',
                  'to'   => '-'.$des['user_id'],
                  'fromPort' => 'B',
                  'toPort' => 'T',
                );
        }else{
          $posi = array(
                  'from' => '-'.$des['teamlead_id'],
                  'to'   => '-'.$des['user_id'],
                  'fromPort' => 'B',
                  'toPort' => 'T',
                );
        }
      }else{
        $res = array(
          'category' =>'bluebox',
          'text'     =>'Blue Box',
          'key'      =>'-'.$des['user_id'],
          'loc'      => '',
          'name'     => $des['fullname'],
          "department" =>$des['deptname'],
          "position"=>$des['designation'],
          "picture" =>$pro_pic
        );
        $posi = array(
                'from' => '-'.$des['teamlead_id'],
                'to'   => '-'.$des['user_id'],
                'fromPort' => 'B',
                'toPort' => 'T',
              );
      }
      $result['nodeDataArray'][] = $res;
      $result['linkDataArray'][] = $posi;


  }
      $i++;

}



// $grades_all = $this->db->get_where('grades',array('grade_id !='=>1))->result_array();

// foreach ($grades_all as $gradeing) {
//   $des_grades = $this->db->select('*')
//                        ->from('designation D')
//                        ->join('users U','U.designation_id = D.id')
//                        ->join('account_details AD','AD.user_id = U.id')
//                        ->where('D.grade',$gradeing['grade_id'])
//                        ->where('U.activated',1)
//                        ->where('U.banned',0)
//                        ->get()->result_array();


//   foreach($des_grades as $dess)
//   {
//       $res = array(
//         'category' =>'orangebox',
//         'text'     =>'Orange Box',
//         'key'      =>'-'.$dess['user_id'],
//         'loc'      => '',
//         'name'     => $dess['fullname'],
//         "department" =>$dess['deptname'],
//         "position"=>$dess['designation']
//       );
//         $posi = array(
//                 'from' => '-'.$dess['teamlead_id'],
//                 'to'   => '-'.$dess['user_id'],
//                 'fromPort' => 'B',
//                 'toPort' => 'T',
//               );
//       $result['nodeDataArray'][] = $res;
//       // if($i == 0){
//         $result['linkDataArray'][] = $posi;
//       // }

//   }
//       // $i++;

// }

// $i =1;
// $r =0;

  // $des_grade = $this->db->select('*')
  //                      ->from('designation D')
  //                      ->join('users U','U.designation_id = D.id')
  //                      ->join('account_details AD','AD.user_id = U.id')
  //                      ->where('D.grade',$all_grades['grade_id'])
  //                      ->where('U.activated',1)
  //                      ->where('U.banned',0)
  //                      ->get()->result_array();

// foreach($des_grade as $des)
// {
//     $res = array(
//       'category' =>'greenbox',
//       'text'     =>'Green Box',
//       'key'      =>'-'.$des['user_id'],
//       'loc'      => '',
//       'name'     => $des['fullname'],
//       "department" =>$des['deptname'],
//       "position"=>$des['designation']
//     );
//     // $posi = array(
//     //         'from' => '-'.$i,
//     //         'to'   => '',
//     //         'fromPort' => 'B',
//     //         'toPort' => 'T',
//     //       );
//     $result['nodeDataArray'][] = $res;
//     $i++;

// }


// foreach($des_grade as $des)
// {
//     $json_employee = array();
//     $json_emp_pos = array();
//     if($r == 0){
//       $cnt_dept = (count($des_grade) + 1);
//     }else{
//       $cnt_dept = ($r + 1); 
//     }


//     $all_employees = $this->db->select('*')
//                           ->from('users U')
//                           ->join('account_details AD','AD.user_id = U.id')
//                           ->where('U.teamlead_id',$des['user_id'])
//                           ->get()->result_array();


//       foreach($all_employees as $employee)
//       {
//          $resu = array(
//             'category' =>'graybox',
//             'text'     =>'Gray Box',
//             'key'      =>'-'.$employee['user_id'],
//             'loc'      => '',
//             'name'     => $employee['fullname'],
//             "department" =>$employee['deptname'],
//             "position"=>$employee['designation']
//            );
//           //  $pos = array(
//           //   'from' => '-'.$j,
//           //   'to'   => '-'.$cnt_dept,
//           //   'fromPort' => 'B',
//           //   'toPort' => 'T',
//           // );

//           //  $pos = array(
//           //   'from' => '-'.$cnt_dept,
//           //   'to'   => '',
//           //   'fromPort' => 'B',
//           //   'toPort' => 'T',
//           // );
//           $result['nodeDataArray'][] = $resu;
//           // $result['linkDataArray'][] = $pos;
//           $r = $cnt_dept++;
//        }
//     $j++;

// }

?>
<body onload ="init()">
<script src="<?php echo base_url(); ?>assets/js/org_chart.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jscolor.js"></script>
<link href="<?php echo base_url(); ?>assets/css/org_chartSamples.css" rel="stylesheet" type="text/css" />  <!-- you don't need to use this -->
<script src="<?php echo base_url(); ?>assets/js/org_chartSamples.js"></script>  <!-- this is only for the GoJS Samples framework -->
<!-- <script src="js/jquery.js"></script>    -->
<script id="code"> 
   function init() {     
    var $ = go.GraphObject.make;  // for conciseness in defining templates
    myDiagram =  $(go.Diagram, "myDiagram",  // must name or refer to the DIV HTML element
          {
            initialContentAlignment     : go.Spot.Top,  
            layout                      : $(go.TreeLayout,{ angle: 90, layerSpacing: 20 }), // specify a Diagram.layout that arranges trees 
            allowDrop                   : true,  // must be true to accept drops from the Palette
            "LinkDrawn"                 : showLinkLabel,  // this DiagramEvent listener is defined below
            "LinkRelinked"              : showLinkLabel,
            "animationManager.duration" : 800, // slightly longer than default (600ms) animation
            "undoManager.isEnabled"     : true // enable undo & redo
          }); 
    // helper definitions for node templates
    function nodeStyle() {
      return [ 
        new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
        { 
          locationSpot: go.Spot.Center, 
          mouseEnter: function (e, obj) { showPorts(obj.part, true); },
          mouseLeave: function (e, obj) { showPorts(obj.part, false); }
        } 
      ];
    } 
    function makePort(name, spot, output, input) { 
      return $(go.Shape, "Circle",
       {
        fill         : "transparent",
        stroke       : null,  // this is changed to "white" in the showPorts function
        desiredSize  : new go.Size(8, 8),
        alignment    : spot, alignmentFocus: spot,  // align the port on the main Shape
        portId       : name,  // declare this object to be a "port"
        fromSpot     : spot, toSpot: spot,  // declare where links may connect at this port
        fromLinkable : output, toLinkable: input,  // declare whether the user may draw links to/from here
        cursor       : "pointer"  // show a different cursor to indicate potential link point
       });
    }
    var lightText = 'whitesmoke';
  //function nodeDoubleClick(e, obj) { alert('clicked'); }
  function textStyle() {
       return { font: "10pt  Segoe UI,sans-serif", stroke: "black" };
    } 
  function onSelectionChanged(e) {
    var node = e.diagram.selection.first();
    if (node instanceof go.Node) {
      updateProperties(node.data);
    }else{
      updateProperties(null);
    }
  }
  
  function updateProperties(data) {
    // jQuery("#dmt").addClass('in').css('display','block');
    // jQuery("#org_overlay").css('display','block');
    
    if (data === null) { 
      document.getElementById("name").value        = ""; 
      document.getElementById("position").value    = "";
      document.getElementById("department").value  = "";
      document.getElementById("depart_name").value = "";
    }else{    
      if(!data.color){ 
         if(data.category == 'textbx1'){  data.color = '#67A14D'; data.level = 1; }
         else if(data.category == 'textbx2'){ data.color = '#3262C0'; data.level = 2; }
         else if(data.category == 'textbx3'){ data.color = '#9F9A96'; data.level = 3; }
         else if(data.category == 'textbx4'){ data.color = '#DC873D'; data.level = 4; }
         else if(data.category == 'textbx5'){ data.color = '#F0C439'; data.level = 5; } 
      } 
      if(!data.position_id){ 
          data.position_id = '';
      } 
      document.getElementById("department").value   = "Department"; 
      document.getElementById("name").value         = data.name || "";  
      document.getElementById("position").value     = data.position_id || "";
      document.getElementById("department").value   = data.department_id || ""; 
    }
   }  
    
   var base_url  = ''; 
   //MAIN BOX Script
   myDiagram.nodeTemplateMap.add("greenbox",  // the default category
      $(go.Node, "Auto", nodeStyle(),
    { doubleClick: onSelectionChanged },
      $(go.Shape, "RoundedRectangle",    
          {
      name: "SHAPE",  fill: "#D6DBDF", stroke: null,cursor: "pointer", width : 230
          }, 
      new go.Binding("fill", "color").makeTwoWay(),
       new go.Binding("width", "width").makeTwoWay(),
        new go.Binding("height", "height").makeTwoWay()
    ),
    $(go.Panel, "Horizontal", 
        { 
        alignment  : go.Spot.Left
          },
          $(go.Picture,base_url+"assets/images/download.png",
            {
              name       : 'Picture', 
              desiredSize: new go.Size(39, 50),
              margin     : new go.Margin(6, 8, 6, 10),
            },
      new go.Binding("source", "picture").makeTwoWay() 
           ), 
           $(go.Panel, "Table",
            { 
              margin          : new go.Margin(6, 10, 0, 3),
              defaultAlignment: go.Spot.Left
            },
            $(go.RowColumnDefinition, { column: 2, width: 4 }), 
      $(go.TextBlock, "Name",textStyle(), 
              {
                row: 0, column: 0, columnSpan: 5,
                font: "10pt Segoe UI,sans-serif",
                editable: false, isMultiline: true,
                minSize: new go.Size(10, 16)
              },
              new go.Binding("text", "name").makeTwoWay()), 
        $(go.TextBlock, "Department", textStyle(), 
              {
                row: 2, column: 0, columnSpan: 5,
                font: "10pt Segoe UI,sans-serif",
                editable: false, isMultiline: false,
                minSize: new go.Size(10, 16)
              },
              new go.Binding("text", "department").makeTwoWay()), 
        $(go.TextBlock, "Position",textStyle(), 
              {
                row: 3, column: 0, columnSpan: 5,
                font: "10pt Segoe UI,sans-serif",
                editable: false, isMultiline: false,
                minSize: new go.Size(10, 16)
              },
              new go.Binding("text", "position").makeTwoWay())
        
           )  // end Table Panel
        ), // end Horizontal Panel    
        // four named ports, one on each side:
        makePort("T", go.Spot.Top, false, true),
        makePort("L", go.Spot.Left, true, true),
        makePort("R", go.Spot.Right, true, true),
        makePort("B", go.Spot.Bottom, true, false)
      ));   
    
    myDiagram.nodeTemplateMap.add("bluebox",  // the default category
      $(go.Node, "Auto", nodeStyle(),
    { doubleClick: onSelectionChanged },
      $(go.Shape, "RoundedRectangle",    
          {
      name: "SHAPE",  fill: "#D6DBDF", stroke: null,cursor: "pointer", width : 230
          }, 
      new go.Binding("fill", "color").makeTwoWay(),
       new go.Binding("width", "width").makeTwoWay(),
        new go.Binding("height", "height").makeTwoWay()
    ),
    $(go.Panel, "Horizontal", 
        { 
        alignment  : go.Spot.Left
          },
          $(go.Picture,base_url+"assets/images/download.png",
            {
              name       : 'Picture', 
              desiredSize: new go.Size(39, 50),
              margin     : new go.Margin(6, 8, 6, 10),
            },
      new go.Binding("source", "picture").makeTwoWay() 
           ), 
           $(go.Panel, "Table",
            { 
              margin          : new go.Margin(6, 10, 0, 3),
              defaultAlignment: go.Spot.Left
            },
            $(go.RowColumnDefinition, { column: 2, width: 4 }), 
      $(go.TextBlock, "Name",textStyle(), 
              {
                row: 0, column: 0, columnSpan: 5,
                font: "10pt Segoe UI,sans-serif",
                editable: false, isMultiline: true,
                minSize: new go.Size(10, 16)
              },
              new go.Binding("text", "name").makeTwoWay()), 
        $(go.TextBlock, "Position",textStyle(), 
              {
                row: 3, column: 0, columnSpan: 5,
                font: "10pt Segoe UI,sans-serif",
                editable: false, isMultiline: false,
                minSize: new go.Size(10, 16)
              },
              new go.Binding("text", "position").makeTwoWay()),
        $(go.TextBlock, "Department", textStyle(), 
              {
                row: 2, column: 0, columnSpan: 5,
                font: "10pt Segoe UI,sans-serif",
                editable: false, isMultiline: false,
                minSize: new go.Size(10, 16)
              },
              new go.Binding("text", "department").makeTwoWay()) 
           )  // end Table Panel
        ), // end Horizontal Panel    
        // four named ports, one on each side:
        makePort("T", go.Spot.Top, false, true),
        makePort("L", go.Spot.Left, true, true),
        makePort("R", go.Spot.Right, true, true),
        makePort("B", go.Spot.Bottom, true, false)
      ));  
    
    myDiagram.nodeTemplateMap.add("orangebox",  // the default category
      $(go.Node, "Auto", nodeStyle(),
    { doubleClick: onSelectionChanged },
      $(go.Shape, "RoundedRectangle",    
          {
      name: "SHAPE",  fill: "#D6DBDF", stroke: null,cursor: "pointer", width : 230
          }, 
      new go.Binding("fill", "color").makeTwoWay(),
       new go.Binding("width", "width").makeTwoWay(),
        new go.Binding("height", "height").makeTwoWay()
    ),
    $(go.Panel, "Horizontal", 
        { 
        alignment  : go.Spot.Left
          },
          $(go.Picture,base_url+"assets/images/download.png",
            {
              name       : 'Picture', 
              desiredSize: new go.Size(39, 50),
              margin     : new go.Margin(6, 8, 6, 10),
            },
      new go.Binding("source", "picture").makeTwoWay() 
           ), 
           $(go.Panel, "Table",
            { 
              margin          : new go.Margin(6, 10, 0, 3),
              defaultAlignment: go.Spot.Left
            },
            $(go.RowColumnDefinition, { column: 2, width: 4 }), 
      $(go.TextBlock, "Name",textStyle(), 
              {
                row: 0, column: 0, columnSpan: 5,
                font: "10pt Segoe UI,sans-serif",
                editable: false, isMultiline: true,
                minSize: new go.Size(10, 16)
              },
              new go.Binding("text", "name").makeTwoWay()), 
        $(go.TextBlock, "Position",textStyle(), 
              {
                row: 3, column: 0, columnSpan: 5,
                font: "10pt Segoe UI,sans-serif",
                editable: false, isMultiline: false,
                minSize: new go.Size(10, 16)
              },
              new go.Binding("text", "position").makeTwoWay()),
        $(go.TextBlock, "Department", textStyle(), 
              {
                row: 2, column: 0, columnSpan: 5,
                font: "10pt Segoe UI,sans-serif",
                editable: false, isMultiline: false,
                minSize: new go.Size(10, 16)
              },
              new go.Binding("text", "department").makeTwoWay()) 
           )  // end Table Panel
        ), // end Horizontal Panel    
        // four named ports, one on each side:
        makePort("T", go.Spot.Top, false, true),
        makePort("L", go.Spot.Left, true, true),
        makePort("R", go.Spot.Right, true, true),
        makePort("B", go.Spot.Bottom, true, false)
      ));  
    
    
    myDiagram.nodeTemplateMap.add("graybox",  // the default category
      $(go.Node, "Auto", nodeStyle(),
    { doubleClick: onSelectionChanged },
      $(go.Shape, "RoundedRectangle",    
          {
      name: "SHAPE",  fill: "#D6DBDF", stroke: null,cursor: "pointer", width : 230
          }, 
      new go.Binding("fill", "color").makeTwoWay(),
       new go.Binding("width", "width").makeTwoWay(),
        new go.Binding("height", "height").makeTwoWay()
    ),
    $(go.Panel, "Horizontal", 
        { 
        alignment  : go.Spot.Left
          },
          $(go.Picture,base_url+"assets/images/download.png",
            {
              name       : 'Picture', 
              desiredSize: new go.Size(39, 50),
              margin     : new go.Margin(6, 8, 6, 10),
            },
      new go.Binding("source", "picture").makeTwoWay() 
           ), 
           $(go.Panel, "Table",
            { 
              margin          : new go.Margin(6, 10, 0, 3),
              defaultAlignment: go.Spot.Left
            },
            $(go.RowColumnDefinition, { column: 2, width: 4 }), 
      $(go.TextBlock, "Name",textStyle(), 
              {
                row: 0, column: 0, columnSpan: 5,
                font: "10pt Segoe UI,sans-serif",
                editable: false, isMultiline: true,
                minSize: new go.Size(10, 16)
              },
              new go.Binding("text", "name").makeTwoWay()), 
        $(go.TextBlock, "Position",textStyle(), 
              {
                row: 3, column: 0, columnSpan: 5,
                font: "10pt Segoe UI,sans-serif",
                editable: false, isMultiline: false,
                minSize: new go.Size(10, 16)
              },
              new go.Binding("text", "position").makeTwoWay()),
        $(go.TextBlock, "Department", textStyle(), 
              {
                row: 2, column: 0, columnSpan: 5,
                font: "10pt Segoe UI,sans-serif",
                editable: false, isMultiline: false,
                minSize: new go.Size(10, 16)
              },
              new go.Binding("text", "department").makeTwoWay()) 
           )  // end Table Panel
        ), // end Horizontal Panel    
        // four named ports, one on each side:
        makePort("T", go.Spot.Top, false, true),
        makePort("L", go.Spot.Left, true, true),
        makePort("R", go.Spot.Right, true, true),
        makePort("B", go.Spot.Bottom, true, false)
      ));  
    
    myDiagram.nodeTemplateMap.add("yellowbox",  // the default category
      $(go.Node, "Auto", nodeStyle(),
    { doubleClick: onSelectionChanged },
      $(go.Shape, "RoundedRectangle",    
          {
      name: "SHAPE",  fill: "#D6DBDF", stroke: null,cursor: "pointer", width : 230
          }, 
      new go.Binding("fill", "color").makeTwoWay(),
       new go.Binding("width", "width").makeTwoWay(),
        new go.Binding("height", "height").makeTwoWay()
    ),
    $(go.Panel, "Horizontal", 
        { 
        alignment  : go.Spot.Left
          },
          $(go.Picture,base_url+"assets/images/download.png",
            {
              name       : 'Picture', 
              desiredSize: new go.Size(39, 50),
              margin     : new go.Margin(6, 8, 6, 10),
            },
      new go.Binding("source", "picture").makeTwoWay() 
           ), 
           $(go.Panel, "Table",
            { 
              margin          : new go.Margin(6, 10, 0, 3),
              defaultAlignment: go.Spot.Left
            },
            $(go.RowColumnDefinition, { column: 2, width: 4 }), 
      $(go.TextBlock, "Name",textStyle(), 
              {
                row: 0, column: 0, columnSpan: 5,
                font: "10pt Segoe UI,sans-serif",
                editable: false, isMultiline: true,
                minSize: new go.Size(10, 16)
              },
              new go.Binding("text", "name").makeTwoWay()), 
        $(go.TextBlock, "Position",textStyle(), 
              {
                row: 3, column: 0, columnSpan: 5,
                font: "10pt Segoe UI,sans-serif",
                editable: false, isMultiline: false,
                minSize: new go.Size(10, 16)
              },
              new go.Binding("text", "position").makeTwoWay()),
        $(go.TextBlock, "Department", textStyle(), 
              {
                row: 2, column: 0, columnSpan: 5,
                font: "10pt Segoe UI,sans-serif",
                editable: false, isMultiline: false,
                minSize: new go.Size(10, 16)
              },
              new go.Binding("text", "department").makeTwoWay()) 
           )  // end Table Panel
        ), // end Horizontal Panel    
        // four named ports, one on each side:
        makePort("T", go.Spot.Top, false, true),
        makePort("L", go.Spot.Left, true, true),
        makePort("R", go.Spot.Right, true, true),
        makePort("B", go.Spot.Bottom, true, false)
      ));  
     
    // replace the default Link template in the linkTemplateMap
    myDiagram.linkTemplate =
      $(go.Link,  // the whole link panel
        {
        routing        : go.Link.AvoidsNodes,
        curve          : go.Link.JumpOver,
        corner         : 5, toShortLength: 4,
        relinkableFrom : true,
        relinkableTo   : true,
        reshapable     : true,
        resegmentable  : true,
        // mouse-overs subtly highlight links:
        mouseEnter: function(e, link) { link.findObject("HIGHLIGHT").stroke = "rgba(30,144,255,0.2)"; },
        mouseLeave: function(e, link) { link.findObject("HIGHLIGHT").stroke = "transparent"; }
        },
        $(go.Shape,  // the highlight shape, normally transparent
          { isPanelMain: true, strokeWidth: 8, stroke: "transparent", name: "HIGHLIGHT" }),
        $(go.Shape,  // the link path shape
          { isPanelMain: true, stroke: "gray", strokeWidth: 2 }),
        $(go.Shape,  // the arrowhead
          { toArrow: "standard", stroke: null, fill: "gray"}),
        $(go.Panel, "Auto",  // the link label, normally not visible
          { visible: false, name: "LABEL", segmentIndex: 2, segmentFraction: 0.5},
          new go.Binding("visible", "visible").makeTwoWay(),
          $(go.Shape, "RoundedRectangle",  // the label shape
            { fill: "#10A8BC", stroke: null }),
          $(go.TextBlock, "Yes",  // the label
            {
              textAlign : "center",
              font      : "10pt helvetica, arial, sans-serif",
              stroke    : "#333333",
              editable  : true
            }, new go.Binding("text", "text").makeTwoWay())
        )
      ); 
    // Make link labels visible if coming out of a "conditional" node.
    // This listener is called by the "LinkDrawn" and "LinkRelinked" DiagramEvents.
    function showLinkLabel(e) {
      var label = e.subject.findObject("LABEL");
      if (label !== null) label.visible = (e.subject.fromNode.data.figure === "Diamond");
    } 
    // temporary links used by LinkingTool and RelinkingTool are also orthogonal:
    myDiagram.toolManager.linkingTool.temporaryLink.routing = go.Link.Orthogonal;
    myDiagram.toolManager.relinkingTool.temporaryLink.routing = go.Link.Orthogonal;
  
    load();  // load an initial diagram from some JSON text

    // initialize the Palette that is on the left side of the page
    myPalette =
      $(go.Palette, "myPalette",  // must name or refer to the DIV HTML element
        {
          "animationManager.duration": 800, // slightly longer than default (600ms) animation
          nodeTemplateMap            : myDiagram.nodeTemplateMap,  // share the templates used by myDiagram
          model                      : new go.GraphLinksModel([  // specify the contents of the Palette
             { category: "greenbox", text: "Green Box" },
       { category: "bluebox", text: "Blue Box" }, 
       { category: "graybox", text: "Gray Box" }, 
       { category: "orangebox", text: "Orange Box" }, 
       { category: "yellowbox", text: "Yellow Box" }, 
          ])
        });
    
   layout(); 
  } 
  // Make all ports on a node visible when the mouse is over the node
  function showPorts(node, show) {
    var diagram = node.diagram;
    if (!diagram || diagram.isReadOnly || !diagram.allowLink) return;
    node.ports.each(function(port) {
        port.stroke = (show ? "black" : null);
      });
  } 
  function layout() {
    myDiagram.startTransaction("change Layout");
    var lay = myDiagram.layout; 
    var cycleRemove = 'CycleDepthFirst';
    if (cycleRemove === "CycleDepthFirst") lay.cycleRemoveOption = go.LayeredDigraphLayout.CycleDepthFirst; 
  
    var layering = "LayerOptimalLinkLength";
    if (layering === "LayerOptimalLinkLength") lay.layeringOption = go.LayeredDigraphLayout.LayerOptimalLinkLength; 
  
    var initialize = "LayerOptimalLinkLength";
    if (initialize === "InitDepthFirstOut") lay.initializeOption = go.LayeredDigraphLayout.InitDepthFirstOut; 
  
    var aggressive = "AggressiveMore"; 
    if (aggressive === "AggressiveMore") lay.aggressiveOption = go.LayeredDigraphLayout.AggressiveMore;
    //TODO implement pack option 
    var packing = 0;
    packing = packing | parseInt(4, 10);
  packing = packing | parseInt(2, 10);
  packing = packing | parseInt(1, 10);
  
    lay.packOption = packing; 
    lay.setsPortSpots = true;
    myDiagram.commitTransaction("change Layout");
  }  
  
  function updateData() { 
    var mem_name        = document.getElementById("name").value;
      if(mem_name == '') mem_name = 'Name'; 
    var mem_position_id = document.getElementById("position").value;
    var mem_position    = document.getElementById("position").options[document.getElementById('position').selectedIndex].text;
    if(mem_position == '') mem_position = 'Position';
    var mem_depart      = document.getElementById("department").value; 
    var dep_name       = document.getElementById("department").options[document.getElementById('department').selectedIndex].text;
    if(dep_name == '') dep_name = 'Department';
    var dep_id          = document.getElementById("department").value; 
    var node = myDiagram.selection.first(); 
    var data = node.data; 
    if (node instanceof go.Node && data !== null) {
    var model = myDiagram.model; 
    if(mem_name == '' || mem_name == 'Name'){
      alert("Please Enter Name.....");
    }else{
           
        model.setDataProperty(data, "name", mem_name); 
        model.setDataProperty(data, "position_id", mem_position_id);
        model.setDataProperty(data, "position", mem_position);
        model.setDataProperty(data, "department", dep_name);
        model.setDataProperty(data, "department_id", dep_id); 
        var image_url = "images/profile_images/dummy.png";
        // var up_img    = document.getElementById("prf_picture_name").value; 
        //alert(up_img);
        // if(up_img != ''){
        //   //var x = 20; // can be any number
        //   //var rand = Math.floor(Math.random()*x) + 1;
        //   image_url       = up_img;
        //   var pic         = node.findObject("Picture");
        //   if(pic){
        //     pic.source      = image_url;
        //     model.setDataProperty(data, "picture", image_url);
        //     $('#prf_picture,#prf_picture_name').val(''); 
        //   }
        // }  
        document.getElementById('dmt').style.display = 'none'; 
    }
    }
  }  
  
  function save() { 
    // console.log(JSON.parse($('#mySavedModel').val()));
        var sts = 0;
        var json_data = JSON.parse(myDiagram.model.toJson());  
    jQuery.each(json_data.nodeDataArray, function(index, value){
      var id   = json_data.nodeDataArray[index].user_id;  
      var cat  = json_data.nodeDataArray[index].category;  
    }); 
    if(sts == 0){ 
       document.getElementById("mySavedModel").value = myDiagram.model.toJson();
        var updated_chart = $('#mySavedModel').val();
       myDiagram.isModified = false; 
       $.post(base_url+'organisation/chart_update/',{updated_chart:updated_chart},function(res){
        console.log(res); 
        if(res == 'success')
        {
            toastr.success('Chart Updated');
            setInterval(function(){ 
              location.reload();
            }, 2000);
        }else{
            toastr.error('Chart not Updated');
        }
       });
    }else if(sts == 1){
      alert('Please select all Position members and levels......');
    } 
  }
  
  function load() {
      myDiagram.model = go.Model.fromJson(document.getElementById("mySavedModel").value);
  } 
   
  
</script> 
<section class="wrapper"> 
<div class="row"> 
<!-- column start -->
<div class="col-md-12">
    <section class="panel" style="min-height:260px" >
        <div class="panel-body">   
      <div id="sample" style="margin-left:0px;">
              <div style="width:100%; white-space:nowrap;">
                <span style="width:100px">
                  <div id="myPalette" style="height: 150px; width:100%; display: none;"></div>
                </span> 
                <span style="display: inline-block; vertical-align: top; padding: 5px; width:100%">
                  <div id="myDiagram" style="border: solid 1px gray; height: 500px"></div> 
                </span>
              </div>  
        
        <div class="col-md-10 m-bot15"> 
                    <button class="btn btn-success btn-sm" id="SaveButton" onclick="save()" type="button" style="margin-left:1%;display: none;"> Save Chart </button> 
              </div>  
        
              <textarea id="mySavedModel" style="width:100%;height:300px;display: none;"><?php echo json_encode($result); ?></textarea>  
             </div>
         </div>
    </section>
  
  <div id="dmt" aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal fade">
     <div class="modal-dialog" style="width:50%">
      <div class="modal-content">
        <div class="modal-header">
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button" onclick="document.getElementById('dmt').style.display = 'none';
               document.getElementById('org_overlay').style.display = 'none';">×</button>
          <h4 class="modal-title"> INFORMATIONS </h4>
        </div>
        <div class="modal-body">
           <div id="propertiesPanel" style="display: block;"> 
            <div class="row m-bot15">    
              <div class="col-md-3">  <b style="margin-left:20%">Name</b>   </div>
               <div class="col-md-5">  
                <input id="name" class="form-control user_name_appnd" type="text" placeholder="Name"> 
                <input id="user_id" class="user_id_appnd" type="hidden"> 
                <input id="user_prf_img" class="user_prf_img_appnd" type="hidden" >
               </div> 
              </div>
            
            <div class="row m-bot15">   
             <div class="col-md-3">  <b style="margin-left:20%">Department</b>   </div>
             <div class="col-md-5">  
              <select class="form-control" id="department" >
                <option value="" dp-name="" disabled selected> Department </option>  
                <?php 
                  $departments = $this->db->get_where('dgt_departments')->result_array();
                  foreach($departments as $department){
                ?>
                <option value="<?php echo $department['deptid']; ?>" dp-name="<?php echo $department['deptname']; ?>"><?php echo $department['deptname']; ?></option> 
              <?php } ?>
                <!-- <option value="2" dp-name="Department"> Department 2 </option> 
                <option value="3" dp-name="Department">  Department 3 </option> 
                <option value="4" dp-name="Department"> Department 4 </option> 
                <option value="5" dp-name="Department">  Department 5 </option>  -->
              </select>  
             </div>
            </div>
             
            <div class="row m-bot15">   
             <div class="col-md-3">  <b style="margin-left:20%">Position</b>   </div>
             <div class="col-md-5">            
              <select class="form-control" id="position" >  
                <option value="" dp-name=""> Position </option> 
                <option value="1" dp-name="Position">  Position 1 </option> 
                <option value="2" dp-name="Position"> Position 2 </option> 
                <option value="3" dp-name="Position">  Position 3 </option> 
                <option value="4" dp-name="Position"> Position 4 </option> 
                <option value="5" dp-name="Position">  Position 5 </option> 
              </select>   
             </div>
            </div> 
            <div class="row">  
             <div class="col-md-12"> <hr style="margin:10px " /> </div>  
            </div> 
            <div class="row">  
             <div class="col-md-9"> &nbsp;  </div> 
             <div class="col-md-3">  
                <button class="btn btn-success btn-sm" onClick="updateData();" type="button"> Update </button> 
               <button class="btn btn-danger btn-sm"  type="button" onClick=" document.getElementById('dmt').style.display = 'none'; document.getElementById('org_overlay').style.display = 'none';"> Cancel </button> 
             </div> 
            </div>  
           </div>
         </div>      
      </div>
    </div>
    </div> 
  
</div>
<!-- column end --> 
</div> 
<!-- first row  end --> 
</section>  
<!-- <script>  
$(document).on('change', 'input[name=prf_picture]', function(){
  var url  = 'profile_image_upload.php'; 
  var formData = new FormData(document.getElementById('chart_prf_picture'));  
  $.ajax({
      url        : url,  
      type       : 'POST',
      enctype    : 'multipart/form-data',
      data       : formData,
      cache      : false,           
      contentType: false,
      processData: false,
      success    : function(res) { 
                                      if(res != 'Invalid'){
                                $('#prf_picture_name').val(res); 
                      }
                     }
    });
});
</script> -->

</body>  
</html> 

