<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head><title></title></head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=320, target-densitydpi=device-dpi">
    <p><?=strftime("%b %d, %Y", time()); ?> </p>
    <p>Hi Admin <br> <br>
    	Estimate <?=$estimate->reference_no?> has been <?=$status?>: <br><br>

    	ESTIMATE REF: <?=$estimate->reference_no?><br>
    	CLIENT NAME: <?=Client::view_by_id($estimate->client)->company_name;?><br>
    	AMOUNT: <?=Applib::format_currency($estimate->currency,$amount);?><br>
    </p>

		<p>You can view the estimate <a href="<?=base_url()?>estimates/view/<?=$estimate->est_id?>">View Estimate</a></p>
		<br>

		------------------------------

		<p>Regards </p> <br>
</body>
</html>
