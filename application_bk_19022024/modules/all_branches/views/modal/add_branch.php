<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('add_branch')?></h4>
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal','id'=>'branchlisttable'); echo form_open_multipart(base_url().'all_branches/add_branch',$attributes); ?>
			<div class="modal-body">
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('branch_name')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" class="form-control" placeholder="<?=lang('branch_name')?>" name="branch_name">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('prefix')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" class="form-control" placeholder="<?=lang('prefix')?>" name="branch_prefix">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('entity_admin')?></label>
					<div class="col-lg-8">
					<select class="select2-option form-control" style="width:100%;"  id="employee_id"  multiple>
						<option value=""  disabled><?=lang('entity_admin')?></option>
						<?php
						if(!empty($users))	{
							foreach ($users as $user1){ ?>
								<option value="<?=$user1['id']?>"><?=$user1['fullname']?></option>
							<?php } ?>
						<?php } ?>
					</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label">Select <?=lang('branch_status')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<select name="branch_status" class="form-control m-b">
							<option value="" selected disabled>Choose status</option>
							<option value="0">Active</option>
							<option value="1">InActive</option>
                        </select>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-lg-4 control-label">Weekend (Working Days) <span class="text-danger"></span></label>
					<div class="col-lg-8">
						<input type="checkbox" value="1" id="1_s" name="weekend[]" >
						<label for="1_s" class="">1st Satuday</label>
						
						<input type="checkbox" value="2" id="2_s" name="weekend[]" >
						<label for="2_s"  class="">2nd Satuday</label>
						
						<input type="checkbox"  value="3" id="3_s" name="weekend[]" >
						<label for="3_s"  class="">3rd Satuday</label>
						
						<input type="checkbox" value="4" id="4_s" name="weekend[]" >
						<label for="4_s"  class="">4th Satuday</label>
						
						<input type="checkbox" value="5" id="5_s" name="weekend[]" >
						<label for="5_s"  class="">5th Satuday</label>
					</div>
					<div class="col-lg-4"></div>
					<div class="col-lg-8">
						<input type="checkbox" value="6" id="6_s" name="weekend[]" >
						<label for="6_s" class="">1st Sunday</label>
						<input type="checkbox" value="7" id="7_s" name="weekend[]" >
						<label for="7_s"  class="">2nd Sunday</label>
						
						<input type="checkbox"  value="8" id="8_s" name="weekend[]" >
						<label for="8_s"  class="">3rd Sunday</label>
						
						<input type="checkbox" value="9" id="9_s" name="weekend[]" >
						<label for="9_s"  class="">4th Sunday</label>
						
						<input type="checkbox" value="10" id="10_s" name="weekend[]" >
						<label for="10_s"  class="">5th Sunday</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('geo_fencing')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<label class="switch">
							<input type="checkbox" name="geo_fencing" value="enable" checked>
							<span class="slider round"></span>
						</label>
					</div>
				</div>
				<div class="form-group" style="margin-bottom:36%">
					<label class="col-lg-4 control-label"><?=lang('address')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input id="pac-input" class="controls form-control" type="text" placeholder="Search Address" name="address"/>
					</div>
					<div class="col-lg-3">
				<div id="map"></div>
			</div>
   					
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('lattiude')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
					<input type="text" class="form-control" placeholder="<?=lang('lattiude')?>" readonly="true" id="latitude" name="lattiude" >
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('longtitude')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
					<input type="text" class="form-control" placeholder="<?=lang('longtitude')?>" id="longitude" readonly="true" name="longtitude"  >
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('radius')?>(<?=lang('in_meter')?>) <span class="text-danger">*</span></label>
					<div class="col-lg-8">
					<input type="number" class="form-control" placeholder="<?=lang('radius')?>" id="radius" name="radius" value="15" >
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('razorpay_xpayroll_id')?></label>
					<div class="col-lg-8">
					<input type="text" class="form-control" placeholder="<?=lang('razorpay_xpayroll_id')?>" id="razorpay_xpayroll_id" name="razorpay_xpayroll_id"  />
					</div>
				</div>

				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('razorpay_xpayroll_key')?></label>
					<div class="col-lg-8">
					<input type="text" class="form-control" placeholder="<?=lang('razorpay_xpayroll_key')?>" id="razorpay_xpayroll_key" name="razorpay_xpayroll_key" >
					</div>
				</div>

				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('logo')?></label>
					<div class="col-lg-8">
					<input type="file" class="form-control" placeholder="<?=lang('entity').' '.lang('logo')?>" id="entity_logo" name="entity_logo"  />
					</div>
				</div>

				
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-close" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-success" id="add_branches"><?=lang('add_branch')?></button>
			</div>
		</form>
	</div>
</div>
<script
      src="https://maps.googleapis.com/maps/api/js?key=<?php echo $this->config->item('gmap_access_key');?>&callback=initAutocomplete&libraries=places&v=weekly"
      defer
    ></script>
<script>
	function initAutocomplete() {
  const map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: 11.1271, lng: 78.6569 },
    zoom: 13,
	fullscreenControl:false,
	mapTypeControl:false,
    mapTypeId: "roadmap",
  });
  // Create the search box and link it to the UI element.
  const input = document.getElementById("pac-input");
  const searchBox = new google.maps.places.SearchBox(input);
  
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
  // Bias the SearchBox results towards current map's viewport.
  map.addListener("bounds_changed", () => {
    searchBox.setBounds(map.getBounds());
  });

  let markers = [];

  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
  searchBox.addListener("places_changed", () => {
    const places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }
    // Clear out the old markers.
    markers.forEach((marker) => {
      marker.setMap(null);
    });
    markers = [];

    // For each place, get the icon, name and location.
    const bounds = new google.maps.LatLngBounds();

    places.forEach((place) => {
      if (!place.geometry || !place.geometry.location) {
        console.log("Returned place contains no geometry");
        return;
      }

      const icon = {
        size: new google.maps.Size(100, 100),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25),
      };
      // Create a marker for each place.
      markers.push(
        new google.maps.Marker({
          map,
          title: place.name,
          position: place.geometry.location,
        })
      );
      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
	  document.getElementById("latitude").value = place.geometry.location.lat();
	  document.getElementById("longitude").value = place.geometry.location.lng();
    });
    map.fitBounds(bounds);
	
  });
  setTimeout(function () {
            google.maps.event.trigger(map, "resize");
            map.setCenter(new google.maps.LatLng(11.1271, 78.6569 ));
        }, 300);
}

window.initAutocomplete = initAutocomplete;
	$(".select2-option").select2({
    minimumInputLength: 3,
    tags: [],
    ajax: {
        url: "<?php echo base_url('all_branches/getEmployees');?>",
        dataType: 'json',
        type: "GET",
        quietMillis: 2,
        data: function (term) {
            return {
                term: term
            };
        },
        processResults: function (data) {
			
            return {
                results: $.map(data, function (item) {
                   return {
                        text: item.fullname+' ('+item.email+')',
                        slug: item.email,
                        id: item.id
                    }
                })
            };
        }
    }
});
$("#employee_id").empty();
	</script>
<style>
	.select2-results__options > li:first-child{
   display: none;
}
.select2-results__message{
	display:block !important;
}
	</style>